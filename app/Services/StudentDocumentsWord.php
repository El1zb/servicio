<?php

namespace App\Services;

use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\Shared\Converter;
use PhpOffice\PhpWord\SimpleType\Jc;
use Carbon\Carbon;

class StudentDocumentsWord
{
    public function generate($student, $documents)
    {
        $phpWord = new PhpWord();

        $phpWord->setDefaultFontName('Arial');
        $phpWord->setDefaultFontSize(10);

        $section = $phpWord->addSection([
            'marginTop'    => Converter::cmToTwip(1.5),
            'marginBottom' => Converter::cmToTwip(1.5),
            'marginLeft'   => Converter::cmToTwip(1.5),
            'marginRight'  => Converter::cmToTwip(1.5),
        ]);

        $periodo = $this->detectarPeriodo($student);

        $tableStyle = [
            'borderSize' => 4,
            'borderColor' => '333333',
            'cellMargin' => 60,
        ];

        $phpWord->addTableStyle('DocumentTable', $tableStyle);
        $table = $section->addTable('DocumentTable');

        // Encabezado: nombre del estudiante + título con periodo
        $table->addRow(400);
        $headerCell1 = $table->addCell(13600, ['gridSpan' => 6, 'bgColor' => 'F5F5F5']);
        $headerCell1->addText(
            mb_strtoupper(
                $student->name . ' ' .
                $student->last_name_paterno . ' ' .
                $student->last_name_materno
            ),
            ['bold' => true, 'size' => 12],
            ['alignment' => Jc::CENTER, 'spaceAfter' => 0]
        );

        $table->addRow(350);
        $headerCell2 = $table->addCell(13600, ['gridSpan' => 6, 'bgColor' => 'F5F5F5']);
        $headerCell2->addText(
            'NUEVO SEGUIMIENTO SERVICIO SOCIAL ' . mb_strtoupper($periodo),
            ['bold' => true, 'size' => 11],
            ['alignment' => Jc::CENTER, 'spaceAfter' => 0]
        );

        $table->addRow(null, ['tblHeader' => true]);
        $table->addCell(500, ['bgColor' => 'E8E8E8'])->addText('No.', ['bold' => true, 'size' => 9], ['alignment' => Jc::CENTER]);
        $table->addCell(3500, ['bgColor' => 'E8E8E8'])->addText('Documento', ['bold' => true, 'size' => 9]);
        $table->addCell(1800, ['bgColor' => 'E8E8E8'])->addText('Estado', ['bold' => true, 'size' => 9], ['alignment' => Jc::CENTER]);
        $table->addCell(2000, ['bgColor' => 'E8E8E8'])->addText('Fecha Límite', ['bold' => true, 'size' => 9], ['alignment' => Jc::CENTER]);
        $table->addCell(1800, ['bgColor' => 'E8E8E8'])->addText('Firman', ['bold' => true, 'size' => 9], ['alignment' => Jc::CENTER]);
        $table->addCell(3000, ['bgColor' => 'E8E8E8'])->addText('Observaciones', ['bold' => true, 'size' => 9]);

        $grouped = collect($documents)->groupBy(function ($doc) {
            if ($doc->custom_limit_date) {
                return Carbon::parse($doc->custom_limit_date)->format('d/m/Y');
            } elseif ($doc->file?->limit_date) {
                return Carbon::parse($doc->file->limit_date)->format('d/m/Y');
            }
            return 'SIN FECHA';
        });

        $contador = 1;

        foreach ($grouped as $fecha => $docs) {
            foreach ($docs as $index => $doc) {

                $file = $doc->file;

                $table->addRow();

                $table->addCell(500)->addText($contador++, ['size' => 9], ['alignment' => Jc::CENTER]);
                $table->addCell(3500)->addText($file->name ?? '', ['size' => 9]);

                $estadoTexto = 'FALTA';
                $estadoStyle = [];
                $estadoTextStyle = ['size' => 9];
                $estadoPara = ['alignment' => Jc::CENTER];

                // Los comentarios siempre mandan sobre el check de aprobado
                if (!empty($doc->comments)) {
                    $estadoTexto = $doc->comments;
                    $estadoStyle = ['bgColor' => 'FFE6E6'];
                    $estadoTextStyle = ['size' => 8];
                } elseif (
                    $doc->status === 'revisado' &&
                    $doc->student_file_path
                ) {
                    $estadoTexto = '✓';
                    $estadoStyle = ['bgColor' => 'E6F4EA'];
                    $estadoTextStyle = ['size' => 10, 'bold' => true];
                }

                $cellEstado = $table->addCell(1800, $estadoStyle);
                $cellEstado->addText($estadoTexto, $estadoTextStyle, $estadoPara);

                // La celda de fecha se combina (vMerge) entre todas las filas del mismo grupo
                if ($index === 0) {
                    $cellFecha = $table->addCell(2000, ['vMerge' => 'restart']);
                    $cellFecha->addText(
                        $fecha !== 'SIN FECHA' ? $fecha : 'No aplica',
                        ['size' => 9],
                        ['alignment' => Jc::CENTER]
                    );
                } else {
                    $table->addCell(2000, ['vMerge' => 'continue']);
                }

                $table->addCell(1800)->addText($file->firman ?? 'No aplica', ['size' => 9], ['alignment' => Jc::CENTER]);
                $table->addCell(3000)->addText($file->observations ?? '', ['size' => 9]);
            }
        }

        $section->addTextBreak();
        $section->addText(
            'Documento generado el ' . now()->format('d/m/Y \a \l\a\s H:i') . ' hrs.',
            ['size' => 8, 'color' => '666666', 'italic' => true],
            ['alignment' => Jc::RIGHT]
        );

        $fileName = 'seguimiento_servicio_social_' . $student->id . '.docx';
        $path = storage_path('app/public/' . $fileName);

        $writer = IOFactory::createWriter($phpWord, 'Word2007');
        $writer->save($path);

        return $fileName;
    }

    private function detectarPeriodo($student)
    {
        if (! empty($student->period)) {
            return $student->period->name ?? $this->generarPeriodoActual();
        }

        return $this->generarPeriodoActual();
    }

    /** Ejemplo: "AGOSTO 2025 - FEBRERO 2026" */
    private function generarPeriodoActual()
    {
        $mesActual = Carbon::now()->month;
        $anioActual = Carbon::now()->year;

        // Agosto-Febrero si estamos entre agosto y enero
        if ($mesActual >= 8 || $mesActual <= 1) {
            $anioInicio = $mesActual >= 8 ? $anioActual : $anioActual - 1;
            $anioFin = $anioInicio + 1;
            return "AGOSTO {$anioInicio} - FEBRERO {$anioFin}";
        }

        return "FEBRERO {$anioActual} - AGOSTO {$anioActual}";
    }
}
