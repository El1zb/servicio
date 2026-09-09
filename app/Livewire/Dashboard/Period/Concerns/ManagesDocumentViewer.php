<?php

namespace App\Livewire\Dashboard\Period\Concerns;

use App\Models\File;

trait ManagesDocumentViewer
{
    // ─── Visor de documentos a pantalla completa (plantillas del periodo) ────────
    // Mismo "look" que el visor de la sección de estudiantes (document-viewer.blade.php):
    // dock inferior en mobile, columna de archivos en escritorio. Aquí solo hay
    // Word/PDF de LA plantilla (nunca archivos de otro documento).

    public bool    $isDocViewerOpen   = false;
    public ?int    $docViewerId       = null;
    public ?string $docViewerFilePath = null;

    public function openDocViewer(int $id, ?string $preferredPath = null): void
    {
        $file = File::find($id);
        if (! $file) return;

        $files = $this->getDocViewerFiles($file);

        $this->docViewerId       = $id;
        $this->docViewerFilePath = $preferredPath ?? ($files[0]['path'] ?? null);
        $this->isDocViewerOpen   = true;
    }

    public function docViewerSelectFile(string $path): void
    {
        $this->docViewerFilePath = $path;
    }

    public function docViewerNavigate(string $direction): void
    {
        $file = $this->getDocViewerFile();
        if (! $file) return;

        $paths = collect($this->getDocViewerFiles($file))->pluck('path')->values();
        $pos   = $paths->search($this->docViewerFilePath);

        if ($pos === false) return;

        $newPos = $direction === 'next' ? $pos + 1 : $pos - 1;

        if ($paths->has($newPos)) {
            $this->docViewerFilePath = $paths[$newPos];
        }
    }

    public function docViewerHasPrev(): bool
    {
        $file = $this->getDocViewerFile();
        if (! $file) return false;

        $paths = collect($this->getDocViewerFiles($file))->pluck('path')->values();
        $pos   = $paths->search($this->docViewerFilePath);

        return $pos !== false && $pos > 0;
    }

    public function docViewerHasNext(): bool
    {
        $file = $this->getDocViewerFile();
        if (! $file) return false;

        $paths = collect($this->getDocViewerFiles($file))->pluck('path')->values();
        $pos   = $paths->search($this->docViewerFilePath);

        return $pos !== false && $pos < $paths->count() - 1;
    }

    public function closeDocViewer(): void
    {
        $this->isDocViewerOpen   = false;
        $this->docViewerId       = null;
        $this->docViewerFilePath = null;
    }

    // ─── Datos para la vista ─────────────────────────────────────────────────────

    public function getDocViewerFile(): ?File
    {
        return $this->docViewerId ? File::find($this->docViewerId) : null;
    }

    /**
     * Los archivos de ESTA plantilla nada más: el Word que deben llenar los
     * estudiantes (si lo hay) y el PDF de ejemplo (si lo hay).
     */
    public function getDocViewerFiles(File $file): array
    {
        $files = [];

        if ($file->file_path) {
            $files[] = [
                'path' => $file->file_path,
                'name' => $file->name_file,
                'type' => 'word',
            ];
        }

        if ($file->example_path) {
            $files[] = [
                'path' => $file->example_path,
                'name' => $file->example_name_file,
                'type' => 'pdf',
            ];
        }

        return $files;
    }
}
