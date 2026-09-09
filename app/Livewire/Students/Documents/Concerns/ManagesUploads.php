<?php

namespace App\Livewire\Students\Documents\Concerns;

use App\Models\Document;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Computed;

trait ManagesUploads
{
    public array $fileUpload        = [];
    public ?int  $uploadDocId       = null;
    public bool  $isUploadModalOpen = false;

    public ?int  $cancelDocId       = null;
    public bool  $isCancelModalOpen = false;

    #[Computed]
    public function uploadDocument(): ?Document
    {
        return $this->uploadDocId ? Document::with('file')->find($this->uploadDocId) : null;
    }

    #[Computed]
    public function cancelDocument(): ?Document
    {
        return $this->cancelDocId ? Document::find($this->cancelDocId) : null;
    }

    // ─── Modal de subida ─────────────────────────────────────────────────────────
    // isUploadModalOpen (booleano) es lo que controla la visibilidad del
    // modal (wire:model), separado de uploadDocId (qué documento). Igual que
    // isDocumentModalOpen/documentId en el admin: si el modal se ligara
    // directo a un id mutable, el ciclo de subida temporal de Livewire podía
    // cerrarlo solo a mitad de la subida.

    public function openUploadModal(int $docId): void
    {
        $document = Document::find($docId);

        if (! $document || ! $document->canUploadFile()) return;

        unset($this->fileUpload[$docId]);
        $this->resetValidation();
        $this->uploadDocId       = $docId;
        $this->isUploadModalOpen = true;
    }

    public function closeUploadModal(): void
    {
        if ($this->uploadDocId !== null) {
            unset($this->fileUpload[$this->uploadDocId]);
        }
        $this->uploadDocId       = null;
        $this->isUploadModalOpen = false;
    }

    public function removeSelectedUpload(): void
    {
        if ($this->uploadDocId !== null) {
            unset($this->fileUpload[$this->uploadDocId]);
        }
    }

    public function confirmUpload(): void
    {
        if ($this->uploadDocId === null) return;

        $this->saveUpload($this->uploadDocId);
    }

    // ─── Guardar / cancelar entrega ─────────────────────────────────────────────

    public function saveUpload(int $docId): void
    {
        if (! $this->student) return;

        $document = Document::findOrFail($docId);
        $file     = $document->file;

        if (! $file) return;

        if (! $document->canUploadFile()) {
            $this->dispatch('notify', type: 'error', message: 'Este documento no permite subir archivos.');
            unset($this->fileUpload[$docId]);
            return;
        }

        $uploadedFile = $this->fileUpload[$docId] ?? null;
        if (! $uploadedFile) return;

        if ($uploadedFile->getClientOriginalExtension() !== 'pdf' || $uploadedFile->getMimeType() !== 'application/pdf') {
            $this->dispatch('notify', type: 'error', message: 'Solo se permiten archivos PDF.');
            unset($this->fileUpload[$docId]);
            return;
        }

        if ($file->max_size && $file->max_size > 0) {
            $maxBytes = $file->max_size * 1024;
            if ($uploadedFile->getSize() > $maxBytes) {
                $this->dispatch('notify', type: 'error',
                    message: "El archivo '{$uploadedFile->getClientOriginalName()}' excede el tamaño máximo de {$file->max_size} KB."
                );
                unset($this->fileUpload[$docId]);
                return;
            }
        }

        if ($document->student_file_path && Storage::disk('local')->exists($document->student_file_path)) {
            Storage::disk('local')->delete($document->student_file_path);
        }

        $periodName     = $this->student->period->name ?? 'Periodo';
        $timestamp      = now()->format('Ymd_His');
        $extension      = $uploadedFile->getClientOriginalExtension();
        $storedFileName = "{$file->name}_{$this->student->control_number}_{$periodName}_{$timestamp}.{$extension}";

        $path = $uploadedFile->storeAs('student_uploads', $storedFileName, 'local');

        $document->update([
            'student_file_path' => $path,
            'student_file_name' => $storedFileName,
            'uploaded_at'       => now(),
            'status'            => in_array($file->upload_mode, ['user_only', 'bidirectional'])
                ? 'en_revision'
                : $document->status,
        ]);

        unset($this->fileUpload[$docId]);
        $this->uploadDocId       = null;
        $this->isUploadModalOpen = false;

        // Si el visor está abierto en este mismo documento (dock mobile, botón
        // "Subir"), lo dejamos mostrando de una vez el archivo recién subido.
        if ($this->isViewerOpen && $this->viewerDocId === $docId) {
            $this->viewerFilePath = $path;
        }

        $this->dispatch('notify', type: 'success', message: "Archivo '{$file->name}' subido correctamente.");
    }

    // ─── Cancelar entrega (modal de confirmación, mismo diseño que "Eliminar
    // cuenta" en configuración) ──────────────────────────────────────────────
    // isCancelModalOpen separado de cancelDocId por el mismo motivo que
    // isUploadModalOpen/uploadDocId arriba.
    //
    // Se puede cancelar tanto una entrega rechazada como una en revisión
    // (para corregir/reemplazar el archivo antes de que el admin la revise) —
    // aprobada no se toca.
    private const CANCELABLE_STATUSES = ['rechazado', 'en_revision'];

    public function openCancelModal(int $docId): void
    {
        $document = Document::find($docId);

        if (! $document || ! $document->canUploadFile() || ! in_array($document->status, self::CANCELABLE_STATUSES, true)) return;

        $this->cancelDocId       = $docId;
        $this->isCancelModalOpen = true;
    }

    public function closeCancelModal(): void
    {
        $this->cancelDocId       = null;
        $this->isCancelModalOpen = false;
    }

    public function confirmCancelUpload(): void
    {
        if ($this->cancelDocId === null) return;

        $this->cancelUpload($this->cancelDocId);

        $this->cancelDocId       = null;
        $this->isCancelModalOpen = false;
    }

    public function cancelUpload(int $docId): void
    {
        if (! $this->student) return;

        $document = Document::findOrFail($docId);

        if (! $document->canUploadFile()) return;

        // Solo se puede cancelar una entrega rechazada o en revisión —
        // aprobada no se toca (ver mismo criterio en submission-documents.blade.php).
        if (! in_array($document->status, self::CANCELABLE_STATUSES, true)) return;

        if ($document->student_file_path && Storage::disk('local')->exists($document->student_file_path)) {
            Storage::disk('local')->delete($document->student_file_path);
        }

        $document->update([
            'student_file_path' => null,
            'student_file_name' => null,
            'uploaded_at'       => null,
            'status'            => 'en_revision',
        ]);

        unset($this->fileUpload[$docId]);

        // Igual que al subir: si el visor sigue abierto en este documento, se
        // reacomoda a lo que quede disponible (ya no está "mi archivo").
        if ($this->isViewerOpen && $this->viewerDocId === $docId) {
            $files = $this->getViewerFiles($document->refresh());
            $this->viewerFilePath = $files[0]['path'] ?? null;
        }

        $this->dispatch('notify', type: 'success', message: "Entrega de '{$document->name}' cancelada correctamente.");
    }
}
