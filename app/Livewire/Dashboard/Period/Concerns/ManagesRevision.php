<?php

namespace App\Livewire\Dashboard\Period\Concerns;

use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

use App\Services\StudentDocumentsWord;
use App\Exports\StudentsExport;

use App\Models\Student;
use App\Models\Document;
use App\Models\File;
use App\Models\FileStudentUpload;

trait ManagesRevision
{
    // ========================= Propiedades =========================

    public string  $searchRevision   = '';
    public ?int    $careerFilter     = null;
    public ?string $statusFilter     = null;
    public ?int    $viewingStudentId = null;
    public string  $reviewDocSearch  = '';
    public ?int    $viewingIndividualFileId = null;

    // Quick Review
    public         $quickReviewDoc          = null;
    public string  $quickReviewComments     = '';
    public ?string $quickReviewPreviewUrl   = null;
    public         $nextPendingDoc          = null;
    public         $previousPendingDoc      = null;
    public         $individualUploadFile    = null;
    public         $currentIndividualUpload = null;

    public array   $editingComments = [];
    public array   $editingDates    = [];

    public bool    $showQuickReviewModal = false;

    // Admin files
    public ?array  $adminBaseWord    = null;
    public ?array  $adminExamplePdf  = null;

    // Alcance de navegación del visor rápido: null = todos los pendientes
    // del periodo (botón "Revisar pendientes"); id = solo los documentos
    // de ese estudiante (al entrar desde su tarjeta expandida).
    public ?int    $reviewScopeStudentId = null;

    // ========================= Watchers =========================

    public function updatingSearchRevision(): void
    {
        $this->resetPage('revisionPage');
    }

    // ========================= Query =========================

    public function getStudentsRevisionPaginated()
    {
        $individualFileIds = File::where('period_id', $this->periodId)
            ->where('upload_mode', 'admin_only')
            ->where('is_individual', true)
            ->pluck('id');

        $students = Student::with(['career', 'documents' => function ($q) {
                $q->whereHas('file', fn ($query) => $query->where('period_id', $this->periodId));
            }])
            ->where('period_id', $this->periodId)
            ->where('status', 'aprobado')
            ->when($this->careerFilter, fn ($q) => $q->where('career_id', $this->careerFilter))
            ->when($this->statusFilter, fn ($q) => $this->applyRevisionStatusFilter($q, $individualFileIds))
            ->when($this->searchRevision, function ($q) {
                $q->where(function ($query) {
                    $query->where('name', 'like', "%{$this->searchRevision}%")
                          ->orWhere('last_name_paterno', 'like', "%{$this->searchRevision}%")
                          ->orWhere('last_name_materno', 'like', "%{$this->searchRevision}%")
                          ->orWhere('control_number', 'like', "%{$this->searchRevision}%");
                });
            })
            ->orderBy('name')
            ->paginate(20, ['*'], 'revisionPage');

        $students->getCollection()->transform(fn ($s) => $this->decorateStudentRevisionCounters($s, $individualFileIds));

        return $students;
    }

    /**
     * Documentos que el estudiante ya entregó (con archivo subido) para
     * revisar en el visor unificado: solo los de entrega estudiante-admin,
     * nunca plantillas o cargas individuales administradas por el admin.
     */
    public function getStudentDocuments(int $studentId)
    {
        return Document::query()
            ->where('student_id', $studentId)
            ->whereNotNull('student_file_path')
            ->whereHas('file', fn ($q) => $q->where('period_id', $this->periodId)->where('upload_mode', '!=', 'admin_only'))
            ->join('files', 'files.id', '=', 'documents.file_id')
            ->orderBy('files.limit_date', 'asc')
            ->orderBy('files.id', 'asc')
            ->select('documents.*')
            ->get();
    }

    public function getFilteredStudentDocuments(int $studentId)
    {
        $docs = $this->getStudentDocuments($studentId);

        $needle = trim($this->reviewDocSearch);

        if ($needle === '') {
            return $docs;
        }

        $needle = \Illuminate\Support\Str::lower($needle);

        return $docs->filter(
            fn ($doc) => str_contains(\Illuminate\Support\Str::lower($doc->file->name ?? $doc->name), $needle)
        )->values();
    }

    /**
     * Archivos "carga individual" (solo el admin puede subirlos, uno por
     * estudiante) configurados en este periodo, con si ya tienen archivo
     * subido para este estudiante en particular.
     */
    public function getStudentIndividualFiles(int $studentId)
    {
        $files = File::where('period_id', $this->periodId)
            ->where('upload_mode', 'admin_only')
            ->where('is_individual', true)
            ->orderBy('name')
            ->get();

        $uploads = FileStudentUpload::where('student_id', $studentId)
            ->whereIn('file_id', $files->pluck('id'))
            ->get()
            ->keyBy('file_id');

        return $files->map(function ($file) use ($uploads) {
            $file->uploaded = $uploads->has($file->id);
            return $file;
        });
    }

    public function getFilteredIndividualFiles(int $studentId)
    {
        $files = $this->getStudentIndividualFiles($studentId);

        $needle = trim($this->reviewDocSearch);

        if ($needle === '') {
            return $files;
        }

        $needle = \Illuminate\Support\Str::lower($needle);

        return $files->filter(
            fn ($file) => str_contains(\Illuminate\Support\Str::lower($file->name), $needle)
        )->values();
    }

    /**
     * Todos los documentos pendientes de revisar en el periodo (entregados
     * por el estudiante, sin decisión aún), respetando el filtro de
     * carrera activo. Es la cola que recorre el botón "Revisar pendientes"
     * y también el conteo que se muestra ahí.
     */
    private function getPendingDocsQueueQuery()
    {
        return Document::query()
            ->whereHas('file', fn ($q) => $q->where('period_id', $this->periodId)->where('upload_mode', '!=', 'admin_only'))
            ->whereNotNull('student_file_path')
            ->where(fn ($q) => $q->where('status', 'en_revision')->orWhereNull('status'))
            ->when($this->careerFilter, fn ($q) => $q->whereHas('student', fn ($sq) => $sq->where('career_id', $this->careerFilter)))
            ->join('files', 'files.id', '=', 'documents.file_id')
            ->orderBy('files.limit_date', 'asc')
            ->orderBy('documents.id', 'asc')
            ->select('documents.*');
    }

    public function pendingDocsCount(): int
    {
        return $this->getPendingDocsQueueQuery()->count();
    }

    /**
     * Carreras que de verdad tienen estudiantes aprobados en este periodo
     * (para el filtro, no todas las carreras del sistema).
     */
    public function getFilterCareers()
    {
        return \App\Models\Career::whereIn(
            'id',
            Student::where('period_id', $this->periodId)->where('status', 'aprobado')->distinct()->pluck('career_id')
        )->orderBy('name')->get();
    }

    // ========================= Ver documentos del estudiante =========================

    /**
     * Punto de entrada al hacer click en la tarjeta de un estudiante: abre
     * el visor unificado (lista de sus documentos + revisión) en su primer
     * documento entregado. Si no ha subido nada todavía, abre el visor
     * igual pero sin documento seleccionado (solo su info y la lista vacía).
     */
    public function viewStudentDocuments(int $studentId): void
    {
        $this->resetValidation();
        $this->resetErrorBag();

        $this->viewingStudentId     = $studentId;
        $this->reviewScopeStudentId = $studentId;
        $this->reviewDocSearch      = '';

        $docs = $this->getStudentDocuments($studentId);

        if ($docs->isEmpty()) {
            $this->quickReviewDoc          = null;
            $this->quickReviewPreviewUrl   = null;
            $this->nextPendingDoc          = null;
            $this->previousPendingDoc      = null;
            $this->viewingIndividualFileId = null;
            $this->showQuickReviewModal    = true;
            return;
        }

        $this->quickReviewDocument($docs->first()->id);
    }

    /**
     * Abrir en el visor un archivo de "carga individual": no es un
     * Document (esos solo existen una vez el estudiante entra a su
     * portal), es un File directo — el admin sube el archivo por él.
     */
    public function viewIndividualFile(int $fileId): void
    {
        $this->resetValidation();
        $this->resetErrorBag();

        $this->quickReviewDoc       = null;
        $this->nextPendingDoc       = null;
        $this->previousPendingDoc   = null;
        $this->individualUploadFile = null;

        $this->viewingIndividualFileId = $fileId;

        $this->currentIndividualUpload = FileStudentUpload::where('file_id', $fileId)
            ->where('student_id', $this->viewingStudentId)
            ->first();

        $this->quickReviewPreviewUrl = $this->currentIndividualUpload
            ? route('files.show', ['path' => $this->currentIndividualUpload->file_path])
            : null;

        $this->showQuickReviewModal = true;
    }

    public function getViewingIndividualFile()
    {
        return $this->viewingIndividualFileId
            ? File::find($this->viewingIndividualFileId)
            : null;
    }

    public function getViewingStudent()
    {
        if (! $this->viewingStudentId) {
            return null;
        }

        $student = Student::with(['career', 'documents' => function ($q) {
                $q->whereHas('file', fn ($query) => $query->where('period_id', $this->periodId));
            }])
            ->find($this->viewingStudentId);

        return $student ? $this->decorateStudentRevisionCounters($student) : null;
    }

    // ========================= Quick Review =========================

    /**
     * Punto de entrada del botón "Revisar pendientes": abre el primer
     * documento pendiente de todo el periodo (respetando el filtro de
     * carrera). Siguiente/Anterior recorren esa misma cola completa.
     */
    public function reviewAllPending(): void
    {
        $this->reviewScopeStudentId = null;

        $first = $this->getPendingDocsQueueQuery()->first();

        if (! $first) {
            $this->dispatch('notify', type: 'info', message: 'No hay documentos pendientes por revisar.');
            return;
        }

        $this->quickReviewDocument($first->id);
    }

    /**
     * Igual que reviewAllPending, pero acotado a un solo estudiante (botón
     * "Revisar" en su tarjeta).
     */
    public function reviewStudentPending(int $studentId): void
    {
        $this->reviewScopeStudentId = $studentId;

        $first = $this->getPendingDocsQueueQuery()->where('documents.student_id', $studentId)->first();

        if (! $first) return;

        $this->quickReviewDocument($first->id);
    }

    /**
     * Abrir un documento puntual desde la lista del visor unificado:
     * Siguiente/Anterior navegan solo entre los documentos de ese
     * estudiante (no salta a otro estudiante).
     */
    public function reviewDocFromStudent(int $studentId, int $docId): void
    {
        $this->reviewScopeStudentId = $studentId;
        $this->quickReviewDocument($docId);
    }

    public function quickReviewDocument(int $docId): void
    {
        $this->resetValidation();
        $this->resetErrorBag();

        $this->viewingIndividualFileId = null;
        $this->quickReviewDoc = Document::with(['student.career', 'file'])->find($docId);

        if (! $this->quickReviewDoc) {
            session()->flash('error', 'El documento no está disponible para revisión');
            return;
        }

        if ($this->viewingStudentId !== $this->quickReviewDoc->student_id) {
            $this->reviewDocSearch = '';
        }
        $this->viewingStudentId = $this->quickReviewDoc->student_id;

        $file = $this->quickReviewDoc->file;
        $this->resolvePreviewUrl($file);

        $this->quickReviewComments = $this->quickReviewDoc->comments ?? '';

        if ($file->upload_mode !== 'admin_only') {
            $this->editingDates[$docId] = $this->quickReviewDoc->custom_limit_date
                ? Carbon::parse($this->quickReviewDoc->custom_limit_date)->format('Y-m-d')
                : ($file->limit_date ? Carbon::parse($file->limit_date)->format('Y-m-d') : null);
        }

        $this->findNavigationDocs();
        $this->showQuickReviewModal = true;
    }

    public function closeQuickReview(): void
    {
        $this->resetValidation();
        $this->resetErrorBag();

        $this->quickReviewDoc          = null;
        $this->quickReviewComments     = '';
        $this->quickReviewPreviewUrl   = null;
        $this->nextPendingDoc          = null;
        $this->previousPendingDoc      = null;
        $this->individualUploadFile    = null;
        $this->currentIndividualUpload = null;
        $this->adminBaseWord           = null;
        $this->adminExamplePdf         = null;
        $this->showQuickReviewModal    = false;
        $this->reviewScopeStudentId    = null;
        $this->viewingStudentId        = null;
        $this->viewingIndividualFileId = null;
        $this->reviewDocSearch         = '';
    }

    // ========================= Acciones del modal =========================

    public function quickApproveDocument(): void
    {
        if (! $this->quickReviewDoc) return;

        $this->quickReviewDoc->update([
            'status'      => 'revisado',
            'comments'    => $this->quickReviewComments,
            'reviewed_at' => now(),
        ]);

        $this->dispatch('notify', type: 'success', message: 'Documento aprobado correctamente');

        if (! $this->navigateToNextDoc()) {
            $this->closeQuickReview();
        }
    }

    public function quickRejectDocument(): void
    {
        if (! $this->quickReviewDoc) return;

        $this->validate(
            ['quickReviewComments' => 'required|min:3'],
            [
                'quickReviewComments.required' => 'Debes agregar un comentario para rechazar el documento.',
                'quickReviewComments.min'      => 'El comentario es muy corto.',
            ]
        );

        $this->quickReviewDoc->update([
            'status'      => 'rechazado',
            'comments'    => $this->quickReviewComments,
            'reviewed_at' => now(),
        ]);

        $this->dispatch('notify', type: 'error', message: 'Documento rechazado correctamente');

        if (! $this->navigateToNextDoc()) {
            $this->closeQuickReview();
        }
    }

    public function saveComments(): void
    {
        if (! $this->quickReviewDoc) return;

        $this->quickReviewDoc->update(['comments' => $this->quickReviewComments]);
        $this->dispatch('notify', type: 'info', message: 'Comentarios guardados correctamente');
    }

    /**
     * Actualiza la fecha límite personalizada del documento.
     * Se llama ÚNICAMENTE desde el botón "Guardar Fecha" en el modal,
     * ya no se dispara automáticamente con wire:change.
     */
    public function updateDocumentDate(int $documentId): void
    {
        $doc = Document::with('file')->findOrFail($documentId);

        if ($doc->file->upload_mode === 'admin_only') return;

        $generalDate = $doc->file->limit_date ? Carbon::parse($doc->file->limit_date) : null;
        $customDate  = isset($this->editingDates[$documentId])
            ? Carbon::parse($this->editingDates[$documentId])
            : null;

        // Si la fecha personalizada es igual o anterior a la general, se elimina
        if ($generalDate && $customDate && $customDate->lessThanOrEqualTo($generalDate)) {
            $customDate = null;
        }

        $doc->update(['custom_limit_date' => $customDate]);

        $this->editingDates[$documentId] = $customDate
            ? $customDate->format('Y-m-d')
            : ($generalDate ? $generalDate->format('Y-m-d') : null);

        if ($this->quickReviewDoc && $this->quickReviewDoc->id === $documentId) {
            $this->quickReviewDoc->custom_limit_date = $doc->custom_limit_date;
        }

        $this->dispatch('notify', type: 'success', message: 'Fecha límite actualizada correctamente');
    }

    // ========================= Archivo individual =========================

    public function uploadIndividualFile(): void
    {
        if (! $this->viewingIndividualFileId || ! $this->individualUploadFile) return;

        $file = File::findOrFail($this->viewingIndividualFileId);

        $this->validate([
            'individualUploadFile' => 'required|file|mimes:pdf,doc,docx|max:' . ($file->max_size ?? 10240),
        ], [
            'individualUploadFile.required' => 'Debes seleccionar un archivo',
            'individualUploadFile.mimes'    => 'El archivo debe ser PDF, DOC o DOCX',
            'individualUploadFile.max'      => 'El archivo excede el tamaño máximo permitido',
        ]);

        if ($this->currentIndividualUpload?->file_path) {
            Storage::disk('local')->delete($this->currentIndividualUpload->file_path);
        }

        $path     = $this->individualUploadFile->store('files/individual_uploads', 'local');
        $fileName = $this->individualUploadFile->getClientOriginalName();

        FileStudentUpload::updateOrCreate(
            ['file_id' => $file->id, 'student_id' => $this->viewingStudentId],
            ['file_path' => $path, 'name_file' => $fileName]
        );

        $this->dispatch('notify', type: 'success', message: 'Archivo subido correctamente');
        $this->individualUploadFile = null;
        $this->viewIndividualFile($file->id);
    }

    public function deleteIndividualFile(): void
    {
        if (! $this->currentIndividualUpload) return;

        if ($this->currentIndividualUpload->file_path) {
            Storage::disk('local')->delete($this->currentIndividualUpload->file_path);
        }

        $this->currentIndividualUpload->delete();
        $this->currentIndividualUpload = null;
        $this->quickReviewPreviewUrl   = null;

        $this->dispatch('notify', type: 'info', message: 'Archivo eliminado correctamente');
    }

    // ========================= Navegación entre docs =========================

    public function navigateToNextDoc(): bool
    {
        if ($this->nextPendingDoc) {
            $this->quickReviewDocument($this->nextPendingDoc->id);
            return true;
        }
        return false;
    }

    public function navigateToPreviousDoc(): bool
    {
        if ($this->previousPendingDoc) {
            $this->quickReviewDocument($this->previousPendingDoc->id);
            return true;
        }
        return false;
    }

    // ========================= Exportaciones =========================

    public function exportStudentPDF(int $studentId)
    {
        $student = Student::with(['career', 'period', 'documents.file'])->findOrFail($studentId);

        $documents = $student->documents->filter(
            fn ($doc) => $doc->file && $doc->file->period_id == $student->period_id
        );

        $wordService = new StudentDocumentsWord();
        $fileName    = $wordService->generate($student, $documents);

        return response()->download(
            storage_path('app/public/' . $fileName),
            "Seguimiento_{$student->control_number}.docx"
        )->deleteFileAfterSend();
    }

    public function exportExcel()
    {
        $date     = now()->format('Y-m-d');
        $fileName = "estudiantes_periodo_{$this->period->name}_{$date}.xlsx";

        $individualFileIds = File::where('period_id', $this->periodId)
            ->where('upload_mode', 'admin_only')
            ->where('is_individual', true)
            ->pluck('id');

        return Excel::download(
            new StudentsExport($this->careerFilter, $this->periodId, $this->searchRevision, $this->statusFilter, $individualFileIds),
            $fileName
        );
    }

    // ========================= Helpers privados =========================

    private function resolvePreviewUrl(object $file): void
    {
        $this->adminBaseWord           = null;
        $this->adminExamplePdf         = null;
        $this->quickReviewPreviewUrl   = null;
        $this->currentIndividualUpload = null;

        if ($file->upload_mode === 'admin_only') {
            if ($file->is_individual) {
                $this->currentIndividualUpload = FileStudentUpload::where('file_id', $file->id)
                    ->where('student_id', $this->quickReviewDoc->student_id)
                    ->first();

                $this->quickReviewPreviewUrl = $this->currentIndividualUpload
                    ? route('files.show', ['path' => $this->currentIndividualUpload->file_path])
                    : null;
            } else {
                if ($file->example_path) {
                    $this->adminExamplePdf = [
                        'url'  => route('files.show', ['path' => $file->example_path]),
                        'name' => $file->example_name_file,
                    ];
                }
                if ($file->file_path) {
                    $this->adminBaseWord = [
                        'url'  => route('files.show', ['path' => $file->file_path]),
                        'name' => $file->name_file,
                    ];
                }
            }
        } else {
            $this->quickReviewPreviewUrl = $this->quickReviewDoc->student_file_path
                ? route('files.show', ['path' => $this->quickReviewDoc->student_file_path])
                : null;
        }
    }

    private function findNavigationDocs(): void
    {
        if (! $this->quickReviewDoc) return;

        // Con texto en el buscador: la navegación se acota a lo que el
        // buscador está mostrando en ese momento (sin importar el modo),
        // así que si solo coincide un documento no hay a dónde navegar.
        // Sin buscador: con estudiante fijado se navega entre SUS
        // documentos (todos, sin importar estatus); sin estudiante fijado
        // se navega por la cola completa de pendientes del periodo.
        if (trim($this->reviewDocSearch) !== '') {
            $allDocs = $this->getFilteredStudentDocuments($this->quickReviewDoc->student_id)->values();
        } else {
            $allDocs = $this->reviewScopeStudentId
                ? $this->getStudentDocuments($this->reviewScopeStudentId)->values()
                : $this->getPendingDocsQueueQuery()->get()->values();
        }

        $currentIndex = $allDocs->search(fn ($doc) => $doc->id === $this->quickReviewDoc->id);

        $this->nextPendingDoc     = $currentIndex !== false ? ($allDocs[$currentIndex + 1] ?? null) : null;
        $this->previousPendingDoc = $currentIndex !== false ? ($allDocs[$currentIndex - 1] ?? null) : null;
    }

    public function updatedReviewDocSearch(): void
    {
        $this->findNavigationDocs();
    }

    private function applyRevisionStatusFilter($q, $individualFileIds = null): void
    {
        match ($this->statusFilter) {
            'pending' => $q->whereHas('documents', function ($query) {
                $query->whereNotNull('student_file_path')
                      ->where(fn ($q) => $q->where('status', 'en_revision')->orWhereNull('status'));
            }),
            'approved' => $q->whereHas('documents', fn ($query) => $query->where('status', 'revisado')),
            'rejected' => $q->whereHas('documents', fn ($query) => $query->where('status', 'rechazado')),
            'missing_individual' => $q->when(
                ($individualFileIds ?? collect())->isNotEmpty(),
                function ($query) use ($individualFileIds) {
                    $placeholders = implode(',', array_fill(0, $individualFileIds->count(), '?'));
                    $query->whereRaw(
                        "(select count(*) from file_student_uploads
                            where file_student_uploads.student_id = students.id
                            and file_student_uploads.file_id in ($placeholders)) < ?",
                        [...$individualFileIds->all(), $individualFileIds->count()]
                    );
                },
                // Sin documentos individuales configurados en el periodo, a
                // nadie le puede faltar uno: la lista debe quedar vacía, no
                // mostrar a todos los estudiantes sin filtrar.
                fn ($query) => $query->whereRaw('1 = 0')
            ),
            default => null,
        };
    }

    private function decorateStudentRevisionCounters(object $student, $individualFileIds = null): object
    {
        $periodDocs = $student->documents->filter(
            fn ($doc) => $doc->file
                && $doc->file->period_id == $this->periodId
                && $doc->file->upload_mode !== 'admin_only'
                && $doc->student_file_path
        );

        $student->delivered      = $periodDocs->count();
        $student->total          = File::where('period_id', $this->periodId)
                                       ->where('upload_mode', '!=', 'admin_only')
                                       ->count();
        $student->approved_count = $periodDocs->where('status', 'revisado')->count();
        $student->pending_count  = $periodDocs->filter(
            fn ($doc) => $doc->status === 'en_revision' || is_null($doc->status)
        )->count();
        $student->rejected_count = $periodDocs->where('status', 'rechazado')->count();

        $individualFileIds = $individualFileIds ?? File::where('period_id', $this->periodId)
            ->where('upload_mode', 'admin_only')
            ->where('is_individual', true)
            ->pluck('id');

        if ($individualFileIds->isNotEmpty()) {
            $uploaded = FileStudentUpload::where('student_id', $student->id)
                ->whereIn('file_id', $individualFileIds)
                ->count();

            $student->individual_total     = $individualFileIds->count();
            $student->individual_uploaded  = $uploaded;
            $student->individual_missing   = $individualFileIds->count() - $uploaded;
            $student->missing_individual   = $uploaded < $individualFileIds->count();
        } else {
            $student->individual_total    = 0;
            $student->individual_uploaded = 0;
            $student->individual_missing  = 0;
            $student->missing_individual  = false;
        }

        return $student;
    }
}