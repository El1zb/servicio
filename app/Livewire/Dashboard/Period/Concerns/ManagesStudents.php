<?php

namespace App\Livewire\Dashboard\Period\Concerns;

use App\Models\Document;
use App\Models\Student;
use App\Notifications\ProfileApproved;
use App\Notifications\ProfileRejected;
use Illuminate\Support\Arr;

trait ManagesStudents
{
    // ========================= Propiedades =========================

    public string $search               = '';
    public ?string $statusFilterStudents = null;
    public ?int $careerFilterStudents    = null;
    public ?int $semesterFilterStudents  = null;

    public ?object $selectedStudent     = null;
    public bool $showModal              = false;
    public bool $editMode               = false;
    public array $studentData           = [];

    public bool $isRejecting            = false;
    public string $rejectionReason      = '';
    public bool $enteredEditFromCard    = false;
    public int  $editFormInstance       = 0;

    // Navegación entre pendientes (visor rápido)
    public bool $isReviewingQueue       = false;
    public $nextPendingStudent          = null;
    public $previousPendingStudent      = null;
    public int $pendingQueuePosition    = 0;
    public int $pendingQueueTotal       = 0;

    // ========================= Watchers =========================

    public function updated($propertyName): void
    {
        $this->resetValidation($propertyName);
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingStatusFilterStudents(): void
    {
        $this->resetPage();
    }

    public function updatingCareerFilterStudents(): void
    {
        $this->resetPage();
    }

    public function updatingSemesterFilterStudents(): void
    {
        $this->resetPage();
    }

    // ========================= Query =========================

    /** Paginador de estudiantes usado en render(), con todos los filtros. */
    public function getStudentsPaginated()
    {
        $students = Student::with(['campus', 'career', 'semester'])
            ->where('period_id', $this->periodId)
            ->when($this->statusFilterStudents, function ($q) {
                match ($this->statusFilterStudents) {
                    'pending'  => $q->where('status', 'pendiente'),
                    'approved' => $q->where('status', 'aprobado'),
                    'rejected' => $q->where('status', 'rechazado'),
                    default    => null,
                };
            })
            ->when($this->careerFilterStudents, fn ($q) => $q->where('career_id', $this->careerFilterStudents))
            ->when($this->semesterFilterStudents, fn ($q) => $q->where('semester_id', $this->semesterFilterStudents))
            ->when($this->search, function ($q) {
                $q->where(function ($query) {
                    $query->where('name', 'like', "%{$this->search}%")
                        ->orWhere('last_name_paterno', 'like', "%{$this->search}%")
                        ->orWhere('last_name_materno', 'like', "%{$this->search}%")
                        ->orWhere('control_number', 'like', "%{$this->search}%");
                });
            })
            ->orderBy('name')
            ->paginate(12);

        $students->getCollection()->transform(fn ($s) => $this->decorateStudentStatus($s));

        return $students;
    }

    /**
     * Estudiantes pendientes según carrera/semestre (el buscador y el
     * filtro de estatus no aplican a la cola de revisión rápida).
     */
    private function getPendingQueueQuery()
    {
        return Student::where('period_id', $this->periodId)
            ->where('status', 'pendiente')
            ->when($this->careerFilterStudents, fn ($q) => $q->where('career_id', $this->careerFilterStudents))
            ->when($this->semesterFilterStudents, fn ($q) => $q->where('semester_id', $this->semesterFilterStudents))
            ->orderBy('name');
    }

    public function pendingCount(): int
    {
        return $this->getPendingQueueQuery()->count();
    }

    /** Stats del periodo para la barra de la pestaña Estudiantes (2 queries). */
    public function getPeriodStats(): array
    {
        $students = Student::where('period_id', $this->periodId)
            ->selectRaw("
                COUNT(*) as total,
                SUM(CASE WHEN status = 'pendiente' THEN 1 ELSE 0 END) as pending,
                SUM(CASE WHEN status = 'aprobado' THEN 1 ELSE 0 END) as approved
            ")
            ->first();

        $pendingDocuments = Document::whereHas('file', fn ($q) => $q->where('period_id', $this->periodId))
            ->whereNotNull('student_file_path')
            ->where(fn ($q) => $q->where('status', 'en_revision')->orWhereNull('status'))
            ->count();

        return [
            'total'            => (int) $students->total,
            'pending'          => (int) $students->pending,
            'approved'         => (int) $students->approved,
            'pendingDocuments' => $pendingDocuments,
        ];
    }

    /** Carreras con estudiantes en este periodo (no todas las del sistema). */
    public function getFilterCareers()
    {
        return \App\Models\Career::whereIn(
            'id',
            Student::where('period_id', $this->periodId)->distinct()->pluck('career_id')
        )->orderBy('name')->get();
    }

    // ========================= Visor rápido =========================

    /** Abre el visor en el primer pendiente (botón "Revisar pendientes"). */
    public function reviewPending(): void
    {
        $first = $this->getPendingQueueQuery()->first();

        if (! $first) {
            $this->dispatch('notify', type: 'info', message: 'No hay estudiantes pendientes por revisar.');
            return;
        }

        $this->isReviewingQueue = true;
        $this->viewDetails($first->id);
    }

    /** Abre una card individual: aprobar/rechazar aquí solo la cierra. */
    public function openStudentCard(int $studentId): void
    {
        $this->isReviewingQueue = false;
        $this->viewDetails($studentId);
    }

    public function viewDetails(int $studentId): void
    {
        $this->selectedStudent = Student::with(['campus', 'career', 'semester'])
            ->where('period_id', $this->periodId)
            ->findOrFail($studentId);

        $this->editMode     = false;
        $this->isRejecting  = false;
        $this->rejectionReason = '';
        $this->showModal    = true;

        $this->findNavigationStudents();
    }

    public function closeModal(): void
    {
        $this->reset([
            'showModal', 'editMode', 'selectedStudent', 'studentData',
            'isRejecting', 'rejectionReason', 'enteredEditFromCard', 'isReviewingQueue',
            'nextPendingStudent', 'previousPendingStudent',
            'pendingQueuePosition', 'pendingQueueTotal',
        ]);
    }

    public function navigateToNextPending(): bool
    {
        if ($this->nextPendingStudent) {
            $this->viewDetails($this->nextPendingStudent->id);
            return true;
        }
        return false;
    }

    public function navigateToPreviousPending(): bool
    {
        if ($this->previousPendingStudent) {
            $this->viewDetails($this->previousPendingStudent->id);
            return true;
        }
        return false;
    }

    private function findNavigationStudents(): void
    {
        $this->nextPendingStudent     = null;
        $this->previousPendingStudent = null;
        $this->pendingQueuePosition   = 0;
        $this->pendingQueueTotal      = 0;

        if (! $this->isReviewingQueue || ! $this->selectedStudent || $this->selectedStudent->status !== 'pendiente') {
            return;
        }

        $pending      = $this->getPendingQueueQuery()->get()->values();
        $currentIndex = $pending->search(fn ($s) => $s->id === $this->selectedStudent->id);

        if ($currentIndex === false) {
            return;
        }

        $this->nextPendingStudent     = $pending[$currentIndex + 1] ?? null;
        $this->previousPendingStudent = $pending[$currentIndex - 1] ?? null;
        $this->pendingQueuePosition   = $currentIndex + 1;
        $this->pendingQueueTotal      = $pending->count();
    }

    // ========================= Edición =========================

    public function editStudent(int $studentId): void
    {
        $this->enteredEditFromCard = ! $this->showModal;

        $student               = Student::where('period_id', $this->periodId)->findOrFail($studentId);
        $this->selectedStudent = $student;
        $this->studentData     = $student->toArray();
        $this->editMode        = true;
        $this->editFormInstance++;
        $this->showModal       = true;
    }

    public function cancelEdit(): void
    {
        if ($this->enteredEditFromCard) {
            $this->closeModal();
            return;
        }

        $this->editMode    = false;
        $this->studentData = $this->selectedStudent->toArray();
    }

    public function updateStudent(): void
    {
        if (! $this->selectedStudent) return;

        $this->validate(
            $this->studentUpdateRules(),
            $this->studentUpdateMessages()
        );

        $allowedFields = array_map(
            fn ($key) => str_replace('studentData.', '', $key),
            array_keys($this->studentUpdateRules())
        );

        $this->selectedStudent->update(Arr::only($this->studentData, $allowedFields));

        $this->dispatch('notify', type: 'info', message: 'Información actualizada correctamente');
        $this->editMode = false;
        $this->viewDetails($this->selectedStudent->id);
    }

    // ========================= Aprobar / Rechazar =========================

    public function approve(int $studentId): void
    {
        $student = Student::where('period_id', $this->periodId)->find($studentId);

        if (! $student) {
            $this->closeModal();
            return;
        }

        $student->update(['status' => 'aprobado']);
        $student->user?->notify(new ProfileApproved());
        $this->dispatch('notify', type: 'success', message: 'Perfil aprobado correctamente');

        if (! $this->isReviewingQueue || ! $this->navigateToNextPending()) {
            $this->closeModal();
        }
    }

    public function startReject(): void
    {
        $this->isRejecting     = true;
        $this->rejectionReason = '';
    }

    public function cancelReject(): void
    {
        $this->isRejecting     = false;
        $this->rejectionReason = '';
    }

    public function confirmReject(): void
    {
        $this->validate(
            ['rejectionReason' => 'required|min:3'],
            [
                'rejectionReason.required' => 'Debes agregar un motivo para rechazar al estudiante.',
                'rejectionReason.min'      => 'El motivo debe tener al menos 3 caracteres.',
            ]
        );

        if (! $this->selectedStudent) return;

        $studentId = $this->selectedStudent->id;

        $this->selectedStudent->update([
            'status'           => 'rechazado',
            'rejection_reason' => $this->rejectionReason,
        ]);

        $this->selectedStudent->user?->notify(new ProfileRejected($this->rejectionReason));
        $this->dispatch('notify', type: 'error', message: 'Perfil rechazado correctamente');

        $this->isRejecting     = false;
        $this->rejectionReason = '';

        if (! $this->isReviewingQueue || ! $this->navigateToNextPending()) {
            $this->closeModal();
        }
    }

    // ========================= Helpers =========================

    private function decorateStudentStatus(object $student): object
    {
        $student->status_label = match ($student->status) {
            'aprobado'  => 'Aprobado',
            'rechazado' => 'Rechazado',
            default     => 'Pendiente',
        };

        $student->status_badge_class = match ($student->status) {
            'aprobado'  => 'status-badge--approved',
            'rechazado' => 'status-badge--rejected',
            default     => 'status-badge--pending',
        };

        return $student;
    }

    private function studentUpdateRules(): array
    {
        return [
            'studentData.name'                => 'required|string|max:255',
            'studentData.last_name_paterno'   => 'required|string|max:255',
            'studentData.last_name_materno'   => 'required|string|max:255',
            'studentData.curp'                => 'required|string|max:18',
            'studentData.phone'               => 'required|string|max:10',
            'studentData.personal_email'      => 'required|email',
            'studentData.institutional_email' => ['required', 'email', 'regex:/@itsco\.edu\.mx$/i'],
            'studentData.control_number'      => 'required|string|max:10',
            'studentData.system'              => 'required|string',
            'studentData.semester_id'         => 'required|exists:semesters,id',
            'studentData.campus_id'           => 'required|exists:campuses,id',
            'studentData.career_id'           => 'required|exists:careers,id',
            'studentData.reticular_progress'  => 'required|numeric|min:0|max:100',
        ];
    }

    private function studentUpdateMessages(): array
    {
        return [
            'studentData.name.required'                => 'El nombre es obligatorio',
            'studentData.last_name_paterno.required'   => 'El apellido paterno es obligatorio',
            'studentData.last_name_materno.required'   => 'El apellido materno es obligatorio',
            'studentData.curp.required'                => 'La CURP es obligatoria',
            'studentData.curp.max'                     => 'La CURP no puede exceder 18 caracteres',
            'studentData.phone.required'               => 'El teléfono es obligatorio',
            'studentData.phone.max'                    => 'El teléfono no puede exceder 10 caracteres',
            'studentData.personal_email.required'      => 'El correo personal es obligatorio',
            'studentData.personal_email.email'         => 'El correo personal debe ser válido',
            'studentData.institutional_email.required' => 'El correo institucional es obligatorio',
            'studentData.institutional_email.email'    => 'El correo institucional debe ser válido',
            'studentData.institutional_email.regex'    => 'El correo institucional debe terminar con @itsco.edu.mx',
            'studentData.control_number.required'      => 'El número de control es obligatorio',
            'studentData.control_number.max'           => 'El número de control no puede exceder 10 caracteres',
            'studentData.system.required'              => 'El sistema es obligatorio',
            'studentData.semester_id.required'         => 'El semestre es obligatorio',
            'studentData.semester_id.exists'           => 'El semestre seleccionado no es válido',
            'studentData.campus_id.required'           => 'El campus es obligatorio',
            'studentData.campus_id.exists'             => 'El campus seleccionado no es válido',
            'studentData.career_id.required'           => 'La carrera es obligatoria',
            'studentData.career_id.exists'             => 'La carrera seleccionada no es válida',
            'studentData.reticular_progress.required'  => 'El avance reticular es obligatorio',
            'studentData.reticular_progress.numeric'   => 'El avance reticular debe ser un número',
            'studentData.reticular_progress.min'       => 'El avance reticular no puede ser menor a 0',
            'studentData.reticular_progress.max'       => 'El avance reticular no puede ser mayor a 100',
        ];
    }
}
