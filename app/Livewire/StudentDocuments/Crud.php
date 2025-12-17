<?php

namespace App\Livewire\StudentDocuments;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Document;
use App\Models\File;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class Crud extends Component
{
    use WithFileUploads;

    public $fileUpload = [];
    public $previewPath = null;
    public $previewName = null;
    public $student;

    public function mount()
    {
        $this->student = Auth::user()->student;

        if (!$this->student) {
            session()->flash('error', 'No tienes perfil de estudiante.');
            return;
        }

        /*if ($this->student->period_id) {
            $this->assignPendingDocuments();
        }*/

        // Solo asignar documentos si el estudiante está aprobado
        if ($this->student->status === 'aprobado' && $this->student->period_id) {
            $this->assignPendingDocuments();
        }
    }

    public function assignPendingDocuments()
    {
        if (!$this->student) return;

        // 🔹 Desactivar documentos de otros periodos
        Document::where('student_id', $this->student->id)
            ->whereHas('file', fn($q) => $q->where('period_id', '!=', $this->student->period_id))
            ->update(['is_active' => false]);

        // 🔹 Activar o crear documentos del periodo actual
        $files = File::where('period_id', $this->student->period_id)->get();

        /*foreach ($files as $file) {
            Document::updateOrCreate(
                [
                    'student_id' => $this->student->id,
                    'file_id' => $file->id,
                ],
                [
                    'name' => $file->name,
                    'status' => 'en_revision',
                    'is_active' => true,
                    // 🔹 Mantener los archivos previamente subidos si ya existían
                    'student_file_path' => Document::where('student_id', $this->student->id)
                                                    ->where('file_id', $file->id)
                                                    ->value('student_file_path') ?? null,
                    'student_file_name' => Document::where('student_id', $this->student->id)
                                                    ->where('file_id', $file->id)
                                                    ->value('student_file_name') ?? null,
                ]
            );
        }*/


        foreach ($files as $file) {
        $document = Document::firstOrNew([
            'student_id' => $this->student->id,
            'file_id' => $file->id,
        ]);

        $document->name = $file->name;
        $document->is_active = true;

        // Solo asignar 'en_revision' si es un documento nuevo
        if (!$document->exists) {
            $document->status = 'en_revision';
        }

        // Mantener archivos previamente subidos
        $document->student_file_path ??= $document->student_file_path;
        $document->student_file_name ??= $document->student_file_name;

        $document->save();
    }

    }

    public function render()
    {
        $documents = collect();

        if ($this->student && $this->student->status === 'aprobado') {
            $documents = Document::where('student_id', $this->student->id)
            ->with('file')
            ->where('is_active', true)
            ->get()
            ->sortByDesc(function ($doc) {
                $generalDate = $doc->file?->limit_date ? Carbon::parse($doc->file->limit_date) : null;
                $customDate  = $doc->custom_limit_date ? Carbon::parse($doc->custom_limit_date) : null;

                // Mostrar personalizada solo si es mayor que la general
                if ($generalDate && $customDate && $customDate->greaterThan($generalDate)) {
                    return $customDate;
                }
                return $generalDate ?? now();
            })
            ->groupBy(function ($doc) {
                $generalDate = $doc->file?->limit_date ? Carbon::parse($doc->file->limit_date) : null;
                $customDate  = $doc->custom_limit_date ? Carbon::parse($doc->custom_limit_date) : null;

                if ($generalDate && $customDate && $customDate->greaterThan($generalDate)) {
                    return $customDate->format('Y-m-d');
                }
                return $generalDate?->format('Y-m-d') ?? 'Sin fecha';
            });
        }

        return view('livewire.student-documents.crud', compact('documents'));
    }

    public function updatedFileUpload($file, $docId)
    {
        if ($docId) $this->saveUpload($docId);
    }

    public function saveUpload($docId)
{
    if (!$this->student) return;

    $document = Document::findOrFail($docId);
    $file = $document->file;
    if (!$file) return;

    $uploadedFile = $this->fileUpload[$docId] ?? null;
    if (!$uploadedFile) return;

    // 🔹 Validar tamaño máximo
    $maxBytes = $file->max_size * 1024;
    if ($uploadedFile->getSize() > $maxBytes) {
        session()->flash('error', "El archivo '{$uploadedFile->getClientOriginalName()}' excede el tamaño máximo de {$file->max_size} KB.");
        unset($this->fileUpload[$docId]);
        return;
    }

    $extension = $uploadedFile->getClientOriginalExtension();

    // 🔹 Eliminar archivo anterior si existe
    if ($document->student_file_path && Storage::disk('public')->exists($document->student_file_path)) {
        Storage::disk('public')->delete($document->student_file_path);
    }

    // 🔹 Nombre único (agregar timestamp para evitar caché)
    $periodName = $this->student->period->name ?? 'Periodo';
    $timestamp = now()->format('Ymd_His'); // yyyyMMdd_HHmmss
    $storedFileName = "{$file->name}_{$this->student->control_number}_{$periodName}_{$timestamp}.{$extension}";

    // 🔹 Guardar nuevo archivo
    $path = $uploadedFile->storeAs('student_uploads', $storedFileName, 'public');

    // 🔹 Actualizar en BD
    $document->update([
        'student_file_path' => $path,
        'student_file_name' => $storedFileName,
        'uploaded_at' => now(),
        // 👇 Forzamos que cada vez que suba/reemplace quede en revisión
        'status' => 'en_revision',
    ]);

    unset($this->fileUpload[$docId]);
    session()->flash('message', "Archivo '{$file->name}' subido correctamente.");
}





    public function previewFile($path, $name)
    {
        $this->previewPath = $path;
        $this->previewName = $name;
    }

    public function closePreview()
    {
        $this->previewPath = null;
        $this->previewName = null;
    }

    public function formatSize($bytes)
    {
        if ($bytes >= 1024*1024*1024) return round($bytes/(1024*1024*1024),2).' GB';
        if ($bytes >= 1024*1024) return round($bytes/(1024*1024),2).' MB';
        if ($bytes >= 1024) return round($bytes/1024,2).' KB';
        return $bytes.' B';
    }

    // Modal comentarios
public $isCommentsModalOpen = false;
public $selectedDocument = null;

public function openComments($documentId)
{
    $this->selectedDocument = Document::findOrFail($documentId);
    $this->isCommentsModalOpen = true;
}

public function closeComments()
{
    $this->isCommentsModalOpen = false;
    $this->selectedDocument = null;
}

}
