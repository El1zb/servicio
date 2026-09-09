<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use RuntimeException;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class DocxToPdfConverter
{
    private const CACHE_DIR = 'converted';

    // Tope de procesos soffice a la vez (cada uno pesa ~200-300MB de RAM);
    // sin esto, una ráfaga de estudiantes viendo Word podría ahogar el
    // contenedor. Ajustar según núcleos del servidor real.
    private const MAX_CONCURRENT_CONVERSIONS = 3;

    private const SLOT_WAIT_TIMEOUT = 45;

    /**
     * Convierte (con caché) un .docx del disco "local" a PDF vía LibreOffice
     * headless, y devuelve la ruta relativa del PDF resultante.
     *
     * La clave de caché incluye filemtime(), así que reemplazar el .docx
     * invalida el PDF viejo solo. Cache::lock por archivo evita conversiones
     * redundantes sobre el MISMO archivo en paralelo; para archivos
     * DISTINTOS, cada llamada usa su propio perfil de LibreOffice
     * (--env:UserInstallation) — compartir el perfil por defecto hace que
     * las instancias de soffice se pisen entre sí ("cannot be started"). Un
     * semáforo global (acquireConversionSlot) limita además cuántas
     * conversiones corren a la vez en todo el servidor.
     */
    public function convert(string $docxPath): string
    {
        $absoluteSource = Storage::disk('local')->path($docxPath);

        if (! is_file($absoluteSource)) {
            throw new RuntimeException("Archivo no encontrado: {$docxPath}");
        }

        $cacheKey = md5($docxPath).'_'.filemtime($absoluteSource);
        $pdfPath  = self::CACHE_DIR."/{$cacheKey}.pdf";

        if (Storage::disk('local')->exists($pdfPath)) {
            return $pdfPath;
        }

        Cache::lock("docx-convert:{$cacheKey}", 60)->block(30, function () use ($absoluteSource, $pdfPath, $cacheKey) {
            // Otra petición ya convirtió mientras esperábamos el lock.
            if (Storage::disk('local')->exists($pdfPath)) {
                return;
            }

            $outputDir = Storage::disk('local')->path(self::CACHE_DIR);
            if (! is_dir($outputDir)) {
                mkdir($outputDir, 0755, true);
            }

            // Directorio de trabajo exclusivo de esta conversión: LibreOffice
            // nombra el PDF igual que el .docx de origen, así que si dos
            // documentos distintos comparten basename no deben chocar.
            $workDir    = $outputDir."/tmp_{$cacheKey}";
            $profileDir = $workDir.'/lo_profile';
            mkdir($workDir, 0755, true);
            mkdir($profileDir, 0755, true);

            $slot = $this->acquireConversionSlot();

            try {
                $process = new Process([
                    'soffice', '--headless', '--norestore',
                    '-env:UserInstallation=file://'.$profileDir,
                    '--convert-to', 'pdf',
                    '--outdir', $workDir,
                    $absoluteSource,
                ]);
                $process->setTimeout(60);
                $process->run();

                if (! $process->isSuccessful()) {
                    throw new ProcessFailedException($process);
                }

                $generated = glob($workDir.'/*.pdf');
                if (empty($generated)) {
                    throw new RuntimeException('LibreOffice no generó ningún PDF.');
                }

                // rename() dentro del mismo filesystem es atómico: nadie
                // puede leer el archivo destino a medio escribir.
                rename($generated[0], Storage::disk('local')->path($pdfPath));
            } finally {
                $slot->release();
                // workDir incluye el perfil de LibreOffice (con subcarpetas),
                // así que la limpieza tiene que ser recursiva.
                File::deleteDirectory($workDir);
            }
        });

        return $pdfPath;
    }

    /**
     * Semáforo con N locks nombrados: toma el primero libre, o reintenta en
     * ráfagas cortas hasta el timeout. Cada slot es un Cache::lock normal
     * (compatible con el driver "database" que ya usa la app), sin
     * necesitar Redis ni una cola.
     */
    private function acquireConversionSlot()
    {
        $deadline = microtime(true) + self::SLOT_WAIT_TIMEOUT;

        do {
            for ($i = 0; $i < self::MAX_CONCURRENT_CONVERSIONS; $i++) {
                $lock = Cache::lock("docx-convert-slot:{$i}", 60);
                if ($lock->get()) {
                    return $lock;
                }
            }
            usleep(300_000);
        } while (microtime(true) < $deadline);

        throw new RuntimeException('El servidor está ocupado convirtiendo otros documentos, intenta de nuevo en unos segundos.');
    }
}
