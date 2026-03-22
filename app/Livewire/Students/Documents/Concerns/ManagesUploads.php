<?php

namespace App\Livewire\Students\Documents\Concerns;

use App\Models\Document;
use Illuminate\Support\Facades\Storage;

trait ManagesUploads
{
    public array $fileUpload = [];

    public function updatedFileUpload($file, $docId): void
    {
        if ($docId) $this->saveUpload($docId);
    }

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

        if ($document->student_file_path && Storage::disk('public')->exists($document->student_file_path)) {
            Storage::disk('public')->delete($document->student_file_path);
        }

        $periodName     = $this->student->period->name ?? 'Periodo';
        $timestamp      = now()->format('Ymd_His');
        $extension      = $uploadedFile->getClientOriginalExtension();
        $storedFileName = "{$file->name}_{$this->student->control_number}_{$periodName}_{$timestamp}.{$extension}";

        $path = $uploadedFile->storeAs('student_uploads', $storedFileName, 'public');

        $document->update([
            'student_file_path' => $path,
            'student_file_name' => $storedFileName,
            'uploaded_at'       => now(),
            'status'            => in_array($file->upload_mode, ['user_only', 'bidirectional'])
                ? 'en_revision'
                : $document->status,
        ]);

        unset($this->fileUpload[$docId]);

        $this->loadCalendarEvents();
        $this->dispatch('calendar-updated', calendarEvents: $this->calendarEvents);
        $this->dispatch('notify', type: 'success', message: "Archivo '{$file->name}' subido correctamente.");
    }

    public function cancelUpload(int $docId): void
    {
        if (! $this->student) return;

        $document = Document::findOrFail($docId);

        if (! $this->canUploadFile($document)) return;

        if ($document->status === 'revisado') return;

        if ($document->student_file_path && Storage::disk('public')->exists($document->student_file_path)) {
            Storage::disk('public')->delete($document->student_file_path);
        }

        $document->update([
            'student_file_path' => null,
            'student_file_name' => null,
            'uploaded_at'       => null,
            'status'            => 'en_revision',
        ]);

        unset($this->fileUpload[$docId]); // igual que saveUpload

        $this->loadCalendarEvents();
        $this->dispatch('calendar-updated', calendarEvents: $this->calendarEvents);
        $this->dispatch('notify', type: 'success', message: "Entrega de '{$document->name}' cancelada correctamente."); // 👈 $document->name, no $file->name
    }
}