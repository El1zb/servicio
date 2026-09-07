<?php

namespace App\Livewire\Students\Documents\Concerns;

use App\Models\Document;
use Illuminate\Support\Facades\Storage;

trait ManagesUploads
{
    public array $fileUpload        = [];
    public ?int  $uploadDocId       = null;
    public bool  $isUploadModalOpen = false;

    public ?int  $cancelDocId       = null;
    public bool  $isCancelModalOpen = false;

    // ─── Modal de subida ─────────────────────────────────────────────────────────
    // isUploadModalOpen (booleano) es lo que controla la visibilidad del
    // modal (wire:model), separado de uploadDocId (qué documento). Igual que
    // isDocumentModalOpen/documentId en el admin: si el modal se ligara
    // directo a un id mutable, el ciclo de subida temporal de Livewire podía
    // cerrarlo solo a mitad de la subida.

    public function openUploadModal(int $docId): void
    {
        $document = Document::find($docId);

        if (! $document || ! $this->canUploadFile($document)) return;

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

        if (! $this->canUploadFile($document)) {
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

        $this->dispatch('notify', type: 'success', message: "Archivo '{$file->name}' subido correctamente.");
    }

    // ─── Cancelar entrega (modal de confirmación, mismo diseño que "Eliminar
    // cuenta" en configuración) ──────────────────────────────────────────────
    // isCancelModalOpen separado de cancelDocId por el mismo motivo que
    // isUploadModalOpen/uploadDocId arriba.

    public function openCancelModal(int $docId): void
    {
        $document = Document::find($docId);

        if (! $document || ! $this->canUploadFile($document) || $document->status !== 'rechazado') return;

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

        if (! $this->canUploadFile($document)) return;

        // Solo se puede cancelar una entrega rechazada — aprobada o en
        // revisión no se tocan (ver mismo criterio en submission-documents.blade.php).
        if ($document->status !== 'rechazado') return;

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

        $this->dispatch('notify', type: 'success', message: "Entrega de '{$document->name}' cancelada correctamente.");
    }
}
