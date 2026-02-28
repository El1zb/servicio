<?php

namespace App\Livewire\Dashboard\Period\Concerns;

use App\Models\Student;

trait ManagesStudents
{
    // ========================= Propiedades =========================

    public string $search               = '';
    public ?object $selectedStudent     = null;
    public bool $showModal              = false;
    public bool $editMode               = false;
    public array $studentData           = [];

    public string $rejectionReason      = '';
    public bool $showRejectModal        = false;

    public ?string $statusFilterStudents = null;

    // ========================= Watcher =========================

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    // ========================= Query =========================

    /**
     * Devuelve el paginador de estudiantes usado en render().
     */
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
            ->where(function ($q) {
                $q->where('name', 'like', "%{$this->search}%")
                    ->orWhere('last_name_paterno', 'like', "%{$this->search}%")
                    ->orWhere('last_name_materno', 'like', "%{$this->search}%")
                    ->orWhere('control_number', 'like', "%{$this->search}%");
            })
            ->orderBy('name')
            ->paginate(10);

        $students->getCollection()->transform(fn ($s) => $this->decorateStudentStatus($s));

        return $students;
    }

    // ========================= Modales =========================

    public function viewDetails(int $studentId): void
    {
        $this->selectedStudent = Student::with(['campus', 'career', 'semester'])
            ->where('period_id', $this->periodId)
            ->findOrFail($studentId);

        $this->editMode  = false;
        $this->showModal = true;
    }

    public function closeModal(): void
    {
        $this->reset(['showModal', 'editMode', 'selectedStudent', 'studentData']);
    }

    // ========================= Edición =========================

    public function editStudent(int $studentId): void
    {
        $student               = Student::where('period_id', $this->periodId)->findOrFail($studentId);
        $this->selectedStudent = $student;
        $this->studentData     = $student->toArray();
        $this->editMode        = true;
        $this->showModal       = true;
    }

    public function cancelEdit(): void
    {
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

        $this->selectedStudent->update($this->studentData);

        $this->dispatch('notify', type: 'info', message: 'Información actualizada correctamente');
        $this->closeModal();
    }

    // ========================= Aprobar / Rechazar =========================

    public function approve(int $studentId): void
    {
        $student = Student::where('period_id', $this->periodId)->find($studentId);

        if ($student) {
            $student->update(['status' => 'aprobado']);
            $this->dispatch('notify', type: 'success', message: 'Perfil aprobado correctamente');
        }

        $this->closeModal();
    }

    public function reject(int $studentId): void
    {
        $this->selectedStudent = Student::where('period_id', $this->periodId)->findOrFail($studentId);
        $this->rejectionReason = $this->selectedStudent->rejection_reason ?? '';
        $this->showRejectModal = true;
    }

    public function confirmReject(): void
    {
        $this->validate(
            ['rejectionReason' => 'required|min:3'],
            [
                'rejectionReason.required' => 'Debes agregar un motivo para rechazar el documento.',
                'rejectionReason.min'      => 'El motivo debe tener al menos 3 caracteres.',
            ]
        );

        if (! $this->selectedStudent) return;

        $this->selectedStudent->update([
            'status'           => 'rechazado',
            'rejection_reason' => $this->rejectionReason,
        ]);

        $this->dispatch('notify', type: 'error', message: 'Perfil rechazado correctamente');
        $this->reset(['showRejectModal', 'selectedStudent', 'rejectionReason']);
    }

    // ========================= Helpers =========================

    private function decorateStudentStatus(object $student): object
    {
        match ($student->status) {
            'aprobado' => (
                $student->status_label = 'Aprobado'
            ) && (
                $student->status_style = 'background-color: var(--status-icon-bg-approved); color: var(--status-icon-color-approved); padding: 0.25rem 0.5rem; border-radius: 0.5rem; font-weight: 500;'
            ),
            'rechazado' => (
                $student->status_label = 'Rechazado'
            ) && (
                $student->status_style = 'background-color: var(--status-icon-bg-rejected); color: var(--status-icon-color-rejected); padding: 0.25rem 0.5rem; border-radius: 0.5rem; font-weight: 500;'
            ),
            default => (
                $student->status_label = 'Pendiente'
            ) && (
                $student->status_style = 'background-color: var(--status-icon-bg-pending); color: var(--status-icon-color-pending); padding: 0.25rem 0.5rem; border-radius: 0.5rem; font-weight: 500;'
            ),
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