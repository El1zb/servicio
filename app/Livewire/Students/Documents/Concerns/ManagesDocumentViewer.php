<?php

namespace App\Livewire\Students\Documents\Concerns;

use App\Models\Document;
use Illuminate\Support\Facades\Storage;

trait ManagesDocumentViewer
{
    // ─── Visor de documentos a pantalla completa ────────────────────────────────
    // Visor de UN solo documento (la card desde la que se abrió) — muestra solo
    // los archivos de esa card (Word/PDF del admin si los subió, "Mi archivo"
    // si ya lo subí yo), nunca los de otros documentos. Mismo "look" que el
    // visor de revisión del admin, pero sin navegación entre documentos ni
    // panel derecho (acciones exclusivas de admin que no aplican aquí).

    public bool    $isViewerOpen   = false;
    public ?int    $viewerDocId    = null;
    public ?string $viewerFilePath = null;

    public function openDocumentViewer(int $documentId, ?string $preferredPath = null): void
    {
        $document = Document::with('file')->find($documentId);
        if (! $document) return;

        $files = $this->getViewerFiles($document);

        $this->viewerDocId    = $documentId;
        $this->viewerFilePath = $preferredPath ?? ($files[0]['path'] ?? null);
        $this->isViewerOpen   = true;
    }

    public function viewerSelectFile(string $path): void
    {
        $this->viewerFilePath = $path;
    }

    public function viewerNavigate(string $direction): void
    {
        $document = $this->getViewerDocument();
        if (! $document) return;

        $files = $this->getViewerFiles($document);
        $paths = collect($files)->pluck('path')->values();
        $pos   = $paths->search($this->viewerFilePath);

        if ($pos === false) return;

        $newPos = $direction === 'next' ? $pos + 1 : $pos - 1;

        if ($paths->has($newPos)) {
            $this->viewerFilePath = $paths[$newPos];
        }
    }

    public function viewerHasPrev(): bool
    {
        $document = $this->getViewerDocument();
        if (! $document) return false;

        $paths = collect($this->getViewerFiles($document))->pluck('path')->values();
        $pos   = $paths->search($this->viewerFilePath);

        return $pos !== false && $pos > 0;
    }

    public function viewerHasNext(): bool
    {
        $document = $this->getViewerDocument();
        if (! $document) return false;

        $paths = collect($this->getViewerFiles($document))->pluck('path')->values();
        $pos   = $paths->search($this->viewerFilePath);

        return $pos !== false && $pos < $paths->count() - 1;
    }

    public function closeViewer(): void
    {
        $this->isViewerOpen    = false;
        $this->viewerDocId     = null;
        $this->viewerFilePath  = null;
    }

    // ─── Datos para la vista ─────────────────────────────────────────────────────

    public function getViewerDocument(): ?Document
    {
        return $this->viewerDocId ? Document::with('file')->find($this->viewerDocId) : null;
    }

    /**
     * Los archivos de ESTE documento nada más: el mío propio (si ya lo subí)
     * más los del admin (Word/PDF, según el upload_mode — ver
     * Document::filesToDisplay). Nunca mezcla archivos de otro documento.
     *
     * Mi archivo va primero: es lo más importante para mí en ese momento —
     * si ya lo subí, el visor debe abrir mostrándomelo a él, no la plantilla
     * del admin.
     */
    public function getViewerFiles(Document $document): array
    {
        $files = [];

        if ($document->canUploadFile()
            && $document->student_file_path
            && Storage::disk('local')->exists($document->student_file_path)) {
            $files[] = [
                'path' => $document->student_file_path,
                'name' => $document->student_file_name,
                'type' => 'mine',
            ];
        }

        $files = array_merge($files, $document->filesToDisplay());

        // Orden: mi archivo primero (si existe), luego el PDF (se puede ver
        // directo en el visor), al final el Word (solo sirve para descargarlo).
        usort($files, function ($a, $b) {
            $priority = function ($file) {
                if ($file['type'] === 'mine') return 0;
                if (str_ends_with(strtolower($file['path']), '.pdf')) return 1;
                return 2;
            };

            return $priority($a) <=> $priority($b);
        });

        return $files;
    }
}
