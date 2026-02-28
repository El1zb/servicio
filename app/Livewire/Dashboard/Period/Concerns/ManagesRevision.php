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
    public ?int    $expandedStudent  = null;

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

    // ========================= Watchers =========================

    public function updatingSearchRevision(): void
    {
        $this->resetPage('revisionPage');
    }

    // ========================= Query =========================

    public function getStudentsRevisionPaginated()
    {
        $students = Student::with(['career', 'documents' => function ($q) {
                $q->whereHas('file', fn ($query) => $query->where('period_id', $this->periodId));
            }])
            ->where('period_id', $this->periodId)
            ->where('status', 'aprobado')
            ->when($this->careerFilter, fn ($q) => $q->where('career_id', $this->careerFilter))
            ->when($this->statusFilter, fn ($q) => $this->applyRevisionStatusFilter($q))
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

        $students->getCollection()->transform(fn ($s) => $this->decorateStudentRevisionCounters($s));

        return $students;
    }

    public function getStudentDocuments(int $studentId)
    {
        return Document::query()
            ->where('student_id', $studentId)
            ->whereHas('file', fn ($q) => $q->where('period_id', $this->periodId))
            ->join('files', 'files.id', '=', 'documents.file_id')
            ->orderByRaw('documents.student_file_path IS NULL')
            ->orderBy('files.limit_date', 'asc')
            ->orderBy('files.id', 'asc')
            ->select('documents.*')
            ->get();
    }

    // ========================= Expandir estudiante =========================

    public function toggleStudentExpand(int $studentId): void
    {
        $this->expandedStudent = $this->expandedStudent === $studentId ? null : $studentId;
    }

    // ========================= Quick Review =========================

    public function quickReviewDocument(int $docId): void
    {
        $this->resetValidation();
        $this->resetErrorBag();

        $this->quickReviewDoc = Document::with(['student.career', 'file'])->find($docId);

        if (! $this->quickReviewDoc) {
            session()->flash('error', 'El documento no está disponible para revisión');
            return;
        }

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

        $this->dispatch('notify', type: 'success', message: 'Fecha límite actualizada correctamente');
    }

    // ========================= Archivo individual =========================

    public function uploadIndividualFile(): void
    {
        if (! $this->quickReviewDoc || ! $this->individualUploadFile) return;

        $this->validate([
            'individualUploadFile' => 'required|file|mimes:pdf,doc,docx|max:' . ($this->quickReviewDoc->file->max_size ?? 10240),
        ], [
            'individualUploadFile.required' => 'Debes seleccionar un archivo',
            'individualUploadFile.mimes'    => 'El archivo debe ser PDF, DOC o DOCX',
            'individualUploadFile.max'      => 'El archivo excede el tamaño máximo permitido',
        ]);

        $file = $this->quickReviewDoc->file;

        if ($this->currentIndividualUpload?->file_path) {
            Storage::disk('public')->delete($this->currentIndividualUpload->file_path);
        }

        $path     = $this->individualUploadFile->store('files/individual_uploads', 'public');
        $fileName = $this->individualUploadFile->getClientOriginalName();

        FileStudentUpload::updateOrCreate(
            ['file_id' => $file->id, 'student_id' => $this->quickReviewDoc->student_id],
            ['file_path' => $path, 'name_file' => $fileName]
        );

        $this->dispatch('notify', type: 'success', message: 'Archivo subido correctamente');
        $this->quickReviewDocument($this->quickReviewDoc->id);
        $this->individualUploadFile = null;
    }

    public function deleteIndividualFile(): void
    {
        if (! $this->currentIndividualUpload) return;

        if ($this->currentIndividualUpload->file_path) {
            Storage::disk('public')->delete($this->currentIndividualUpload->file_path);
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

        return Excel::download(
            new StudentsExport($this->careerFilter, $this->periodId, $this->searchRevision),
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
                    ? Storage::url($this->currentIndividualUpload->file_path)
                    : null;
            } else {
                if ($file->example_path) {
                    $this->adminExamplePdf = [
                        'url'  => Storage::url($file->example_path),
                        'name' => $file->example_name_file,
                    ];
                }
                if ($file->file_path) {
                    $this->adminBaseWord = [
                        'url'  => Storage::url($file->file_path),
                        'name' => $file->name_file,
                    ];
                }
            }
        } else {
            $this->quickReviewPreviewUrl = $this->quickReviewDoc->student_file_path
                ? Storage::url($this->quickReviewDoc->student_file_path)
                : null;
        }
    }

    private function findNavigationDocs(): void
    {
        if (! $this->quickReviewDoc) return;

        $allDocs      = $this->getStudentDocuments($this->quickReviewDoc->student_id)->values();
        $currentIndex = $allDocs->search(fn ($doc) => $doc->id === $this->quickReviewDoc->id);

        $this->nextPendingDoc     = $allDocs[$currentIndex + 1] ?? null;
        $this->previousPendingDoc = $allDocs[$currentIndex - 1] ?? null;
    }

    private function applyRevisionStatusFilter($q): void
    {
        match ($this->statusFilter) {
            'pending' => $q->whereHas('documents', function ($query) {
                $query->whereNotNull('student_file_path')
                      ->where(fn ($q) => $q->where('status', 'en_revision')->orWhereNull('status'));
            }),
            'approved' => $q->whereHas('documents', fn ($query) => $query->where('status', 'revisado')),
            'rejected' => $q->whereHas('documents', fn ($query) => $query->where('status', 'rechazado')),
            default    => null,
        };
    }

    private function decorateStudentRevisionCounters(object $student): object
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

        return $student;
    }
}