<?php

namespace App\Livewire\StudentDocuments;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Document;
use App\Models\File;
use App\Models\FileStudentUpload;
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

    // 🔹 Añadir esta propiedad para los eventos del calendario
    public $calendarEvents = [];

    public function mount()
    {
        $this->student = Auth::user()->student;

        if (!$this->student) {
            //session()->flash('error', 'No tienes perfil de estudiante.');
           $this->dispatch('notify',
                type: 'error',
                message: 'No tienes perfil de estudiante.'
            );
            return;
        }

        /*if ($this->student->period_id) {
            $this->assignPendingDocuments();
        }*/

            // 🔹 Cargar eventos del calendario
        $this->loadCalendarEvents();


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

        // 🔹 Obtener todos los archivos del periodo actual
        $files = File::where('period_id', $this->student->period_id)->get();

        foreach ($files as $file) {
            // 🔹 Si es individual, verificar que exista un archivo asignado para este estudiante
            /*if ($file->is_individual) {
                $hasIndividualFile = FileStudentUpload::where('file_id', $file->id)
                    ->where('student_id', $this->student->id)
                    ->exists();
                
                // Si no tiene archivo individual asignado, saltar este documento
                if (!$hasIndividualFile) {
                    continue;
                }
            }*/

            
            // 🔹 Si es individual Y no es admin_only, verificar archivo del estudiante
            if ($file->is_individual && $file->upload_mode !== 'admin_only') {
                $hasIndividualFile = FileStudentUpload::where('file_id', $file->id)
                    ->where('student_id', $this->student->id)
                    ->exists();
                
                // Si no tiene archivo individual asignado, saltar este documento
                if (!$hasIndividualFile) {
                    continue;
                }
            }



            // 🔹 Buscar o crear el documento
            $document = Document::firstOrNew([
                'student_id' => $this->student->id,
                'file_id' => $file->id,
            ]);

            // 🔹 Actualizar información básica
            $document->name = $file->name;
            $document->is_active = true;

            // 🔹 Solo asignar 'en_revision' si:
            // 1. Es un documento nuevo (no existe en BD)
            // 2. O el modo permite subida de archivos (user_only o bidirectional)
            if (!$document->exists) {
                // Para documentos nuevos
                if (in_array($file->upload_mode, ['user_only', 'bidirectional'])) {
                    $document->status = 'en_revision';
                } else {
                    // admin_only: no requiere status de revisión
                    $document->status = 'revisado'; // o podrías usar null si prefieres
                }
            }
            // Si ya existe, mantener el status actual (no sobrescribir)

            // 🔹 Mantener archivos previamente subidos si ya existían
            // (no sobrescribir si el estudiante ya había subido algo)
            if ($document->exists) {
                $document->student_file_path = $document->student_file_path;
                $document->student_file_name = $document->student_file_name;
            }

            $document->save();
        }

        // 🔹 OPCIONAL: Desactivar documentos que ya no tienen file asociado
        // (en caso de que un file haya sido eliminado del periodo)
        Document::where('student_id', $this->student->id)
            ->where('is_active', true)
            ->whereDoesntHave('file', function($query) {
                $query->where('period_id', $this->student->period_id);
            })
            ->update(['is_active' => false]);
    }

    // 🔹 Método para cargar eventos del calendario
    // 🔹 Método para cargar eventos del calendario
    public function loadCalendarEvents()
    {
        if (!$this->student) {
            $this->calendarEvents = [];
            return;
        }

        $documents = Document::where('student_id', $this->student->id)
            ->with('file')
            ->where('is_active', true)
            ->get();

        $this->calendarEvents = $documents->map(function ($doc) {
            // 🔹 Solo considerar fechas para documentos que permiten subida
            $uploadMode = $doc->file?->upload_mode ?? 'bidirectional';
            
            // Si es admin_only, no tiene fecha límite (es informativo)
            if ($uploadMode === 'admin_only') {
                return null;
            }

            $generalDate = $doc->file?->limit_date ? Carbon::parse($doc->file->limit_date) : null;
            $customDate = $doc->custom_limit_date ? Carbon::parse($doc->custom_limit_date) : null;

            $effectiveDate = ($generalDate && $customDate && $customDate->greaterThan($generalDate)) 
                ? $customDate 
                : $generalDate;

            // Si no hay fecha efectiva, no agregar al calendario
            if (!$effectiveDate) {
                return null;
            }

            $isExpired = $effectiveDate->isPast();
            $hasFile = !empty($doc->student_file_name);

            return [
                'date' => $effectiveDate->format('Y-m-d'),
                'doc' => [
                    'id' => $doc->id,
                    'name' => $doc->name,
                ],
                'status' => $doc->status,
                'isExpired' => $isExpired,
                'hasFile' => $hasFile,
                'uploadMode' => $uploadMode,
            ];
        })
        ->filter(fn($event) => $event !== null) // 🔹 Filtrar nulls (admin_only sin fecha)
        ->values()
        ->toArray();
    }

    /*public function render()
    {
        $documents = collect();

        if ($this->student && $this->student->status === 'aprobado') {
            $documents = Document::where('student_id', $this->student->id)
                ->with(['file', 'file.studentUploads' => function($query) {
                    $query->where('student_id', $this->student->id);
                }])
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
    }*/

    public function render()
    {
        $documents = collect();
        $adminOnlyDocuments = collect();

        if ($this->student && $this->student->status === 'aprobado') {
            $allDocuments = Document::where('student_id', $this->student->id)
                ->with(['file', 'file.studentUploads' => function($query) {
                    $query->where('student_id', $this->student->id);
                }])
                ->where('is_active', true)
                ->get();

            // 🔹 Separar documentos admin_only de los demás
            $adminOnlyDocuments = $allDocuments
                ->filter(fn($doc) => $doc->file?->upload_mode === 'admin_only');

            $documents = $allDocuments
                ->filter(fn($doc) => $doc->file?->upload_mode !== 'admin_only')
                ->sortByDesc(function ($doc) {
                    $generalDate = $doc->file?->limit_date ? Carbon::parse($doc->file->limit_date) : null;
                    $customDate  = $doc->custom_limit_date ? Carbon::parse($doc->custom_limit_date) : null;

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

        return view('livewire.student-documents.crud', compact('documents', 'adminOnlyDocuments'));
    }

    // 🔹 NUEVO: Método helper para determinar qué archivo mostrar según el modo
    /*public function getFileToDisplay($document)
    {
        if (!$document->file) {
            return null;
        }

        $uploadMode = $document->file->upload_mode;
        $isIndividual = $document->file->is_individual;

        // Si es individual, buscar el archivo específico del estudiante
        if ($isIndividual) {
            $studentUpload = $document->file->studentUploads()
                ->where('student_id', $this->student->id)
                ->first();
            
            return $studentUpload ? [
                'path' => $studentUpload->file_path,
                'name' => $studentUpload->name_file,
                'type' => 'individual'
            ] : null;
        }
        
        // Para archivos no individuales, retornar el archivo general
        return [
            'path' => $document->file->file_path,
            'name' => $document->file->name_file,
            'type' => 'general'
        ]; 
    }
    */

    public function getFileToDisplay($document)
    {
        if (!$document->file) return [];

        $files = [];
        $file = $document->file;
        $uploadMode = $file->upload_mode;
        $isIndividual = $file->is_individual;

        // 🔹 1️⃣ only_user → el estudiante nunca ve archivos admin, solo su subida
        if ($uploadMode === 'user_only') {
            // Solo mostrar si el estudiante subió algo
            if ($document->student_file_path && Storage::disk('public')->exists($document->student_file_path)) {
                $files[] = [
                    'path' => $document->student_file_path,
                    'name' => $document->student_file_name,
                    'type' => 'student_upload'
                ];
            }
            return $files;
        }

        // 🔹 2️⃣ bidirectional → el estudiante puede subir y admin tiene Word y PDF
        if ($uploadMode === 'bidirectional') {
            // Archivos del admin (Word)
            if ($file->file_path && Storage::disk('public')->exists($file->file_path)) {
                $files[] = [
                    'path' => $file->file_path,
                    'name' => $file->name_file,
                    'type' => 'admin_word'
                ];
            }

            // Archivo de ejemplo/admin (PDF)
            if ($file->example_path && Storage::disk('public')->exists($file->example_path)) {
                $files[] = [
                    'path' => $file->example_path,
                    'name' => $file->example_name_file,
                    'type' => 'admin_pdf'
                ];
            }

            return $files;
        }

        // 🔹 3️⃣ only_admin → solo admin sube, puede ser individual o general
        if ($uploadMode === 'admin_only') {
            // Individual → buscar en file_student_uploads para este estudiante
            if ($isIndividual) {
                $studentFile = FileStudentUpload::where('file_id', $file->id)
                    ->where('student_id', $this->student->id)
                    ->first();
                
                if ($studentFile && Storage::disk('public')->exists($studentFile->file_path)) {
                    $files[] = [
                        'path' => $studentFile->file_path,
                        'name' => $studentFile->name_file,
                        'type' => 'individual'
                    ];
                }
            } else {
                // General → admin subió Word y PDF
                if ($file->file_path && Storage::disk('public')->exists($file->file_path)) {
                    $files[] = [
                        'path' => $file->file_path,
                        'name' => $file->name_file,
                        'type' => 'admin_word'
                    ];
                }
                if ($file->example_path && Storage::disk('public')->exists($file->example_path)) {
                    $files[] = [
                        'path' => $file->example_path,
                        'name' => $file->example_name_file,
                        'type' => 'admin_pdf'
                    ];
                }
            }

            return $files;
        }

        return $files;
    }


    // 🔹 NUEVO: Verificar si se puede subir archivo
    public function canUploadFile($document)
    {
        if (!$document->file) {
            return false;
        }

        $uploadMode = $document->file->upload_mode;
        
        // Solo permitir subida en user_only y bidirectional
        return in_array($uploadMode, ['user_only', 'bidirectional']);
    }

    // 🔹 NUEVO: Verificar si debe mostrar archivos del admin
    public function shouldShowAdminFiles($document)
    {
        if (!$document->file) {
            return false;
        }

        $uploadMode = $document->file->upload_mode;
        
        // Mostrar archivos del admin en admin_only y bidirectional
        return in_array($uploadMode, ['admin_only', 'bidirectional']);
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

        // 🔹 VALIDAR que el modo permita subida de archivos
        if (!$this->canUploadFile($document)) {
            $this->dispatch('notify',
                type: 'error',
                message: 'Este documento no permite subir archivos.'
            );
            unset($this->fileUpload[$docId]);
            return;
        }

        $uploadedFile = $this->fileUpload[$docId] ?? null;
        if (!$uploadedFile) return;

        // 🔒 VALIDAR QUE SEA PDF (real)
        if (
            $uploadedFile->getClientOriginalExtension() !== 'pdf' ||
            $uploadedFile->getMimeType() !== 'application/pdf'
        ) {
            $this->dispatch('notify',
                type: 'error',
                message: 'Solo se permiten archivos PDF.'
            );

            unset($this->fileUpload[$docId]);
            return;
        }

        // 🔹 Validar tamaño máximo (solo si está definido)
        if ($file->max_size && $file->max_size > 0) {
            $maxBytes = $file->max_size * 1024;
            
            if ($uploadedFile->getSize() > $maxBytes) {
                $this->dispatch('notify',
                    type: 'error',
                    message: "El archivo '{$uploadedFile->getClientOriginalName()}' excede el tamaño máximo de {$file->max_size} KB."
                );

                unset($this->fileUpload[$docId]);
                return;
            }
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
            // (solo si el modo lo permite)
            'status' => in_array($file->upload_mode, ['user_only', 'bidirectional']) 
                ? 'en_revision' 
                : $document->status,
        ]);

        unset($this->fileUpload[$docId]);

        // 🔹 ACTUALIZAR CALENDARIO DESPUÉS DE SUBIR EL ARCHIVO
        $this->loadCalendarEvents();

        // 🔹 ENVIAR EVENTO JAVASCRIPT PARA ACTUALIZAR ALPINE
        $this->dispatch('calendar-updated', calendarEvents: $this->calendarEvents);

        $this->dispatch('notify',
            type: 'success',
            message: "Archivo '{$file->name}' subido correctamente."
        );
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
