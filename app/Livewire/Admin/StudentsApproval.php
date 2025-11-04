<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Student;
use App\Models\Campus;
use App\Models\Career;
use App\Models\Semester;
use App\Models\Period;

class StudentsApproval extends Component
{
    use WithPagination;

    public $search = '';
    public $selectedStudent;
    public $showModal = false;
    public $editMode = false;
    public $studentData = [];

    // Modal de rechazo
    public $rejectionReason = '';
    public $showRejectModal = false;

    protected $updatesQueryString = ['search'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    // Abrir modal de detalles
    public function viewDetails($studentId)
    {
        $this->selectedStudent = Student::with(['campus', 'career', 'semester', 'period'])->find($studentId);
        $this->editMode = false;
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->selectedStudent = null;
        $this->editMode = false;
    }

    // Abrir modal de edición
    public function editStudent($studentId)
    {
        $student = Student::find($studentId);
        if (!$student) return;

        $this->selectedStudent = $student;
        $this->studentData = $student->toArray();
        $this->editMode = true;
        $this->showModal = true;
    }

    public function cancelEdit()
    {
        $this->editMode = false;

        // Restaurar datos originales
        $this->studentData = [
            'name' => $this->selectedStudent->name,
            'last_name_paterno' => $this->selectedStudent->last_name_paterno,
            'last_name_materno' => $this->selectedStudent->last_name_materno,
            'curp' => $this->selectedStudent->curp,
            'rfc' => $this->selectedStudent->rfc,
            'phone' => $this->selectedStudent->phone,
            'personal_email' => $this->selectedStudent->personal_email,
            'institutional_email' => $this->selectedStudent->institutional_email,
            'control_number' => $this->selectedStudent->control_number,
            'system' => $this->selectedStudent->system,
            'semester_id' => $this->selectedStudent->semester_id,
            'campus_id' => $this->selectedStudent->campus_id,
            'career_id' => $this->selectedStudent->career_id,
            'period_id' => $this->selectedStudent->period_id,
            'reticular_progress' => $this->selectedStudent->reticular_progress,
        ];
    }

    // Guardar cambios del estudiante
    public function updateStudent()
    {
        if (!$this->selectedStudent) return;

        $validated = $this->validate([
            'studentData.last_name_paterno' => 'required|string|max:255',
            'studentData.last_name_materno' => 'required|string|max:255',
            'studentData.name' => 'required|string|max:255',
            'studentData.curp' => 'required|string|max:18',
            'studentData.rfc' => 'nullable|string|max:13',
            'studentData.phone' => 'required|string|max:10',
            'studentData.personal_email' => 'required|email',
            'studentData.institutional_email' => ['required', 'email', 'regex:/@itsco\.edu\.mx$/i'],
            'studentData.control_number' => 'required|string|max:10',
            'studentData.system' => 'required|string',
            'studentData.semester_id' => 'required|exists:semesters,id',
            'studentData.campus_id' => 'required|exists:campuses,id',
            'studentData.career_id' => 'required|exists:careers,id',
            'studentData.period_id' => 'required|exists:periods,id',
            'studentData.reticular_progress' => 'required|numeric|min:0|max:100',
        ]);

        $this->selectedStudent->update($this->studentData);

        session()->flash('message', "La información de {$this->selectedStudent->name} ha sido actualizada.");
        $this->closeModal();
    }

    // Aprobar estudiante
    public function approve($studentId)
    {
        $student = Student::find($studentId);
        if ($student) {
            $student->status = 'aprobado';
            $student->save();

            session()->flash('message', "El perfil de {$student->name} fue aprobado.");
        }
    }

    // Modal de rechazo
    public function reject($studentId)
    {
        $this->selectedStudent = Student::find($studentId);
        $this->rejectionReason = $this->selectedStudent->rejection_reason ?? '';
        $this->showRejectModal = true;
    }

    public function confirmReject()
    {
        if ($this->selectedStudent) {
            $this->selectedStudent->status = 'rechazado';
            $this->selectedStudent->rejection_reason = $this->rejectionReason;
            $this->selectedStudent->save();

            session()->flash('info', "El perfil de {$this->selectedStudent->name} fue rechazado con motivo.");
            $this->showRejectModal = false;
            $this->selectedStudent = null;
            $this->rejectionReason = '';
        }
    }


    public function render()
    {
        $students = Student::with(['campus', 'career', 'semester', 'period'])
            ->where('name', 'like', "%{$this->search}%")
            ->orWhere('last_name_paterno', 'like', "%{$this->search}%")
            ->orWhere('last_name_materno', 'like', "%{$this->search}%")
            ->orderBy('name')
            ->paginate(10);

        $students->getCollection()->transform(function ($student) {
            switch ($student->status) {
                case 'aprobado':
                    $student->status_class = 'bg-green-100 text-green-800';
                    $student->status_label = 'Aprobado';
                    break;
                case 'rechazado':
                    $student->status_class = 'bg-red-100 text-red-800';
                    $student->status_label = 'Rechazado';
                    break;
                default:
                    $student->status_class = 'bg-yellow-100 text-yellow-800';
                    $student->status_label = 'Pendiente';
            }
            return $student;
        });

        return view('livewire.admin.students-approval', [
            'students' => $students,
            'campuses' => Campus::all(),
            'careers' => Career::all(),
            'semesters' => Semester::where('is_active', true)->get(),
            'periods' => Period::all(),
        ]);
    }
}
