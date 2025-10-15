<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Student;

class StudentsApproval extends Component
{
    use WithPagination; // 👈 Esto habilita la paginación en Livewire

    public $search = ''; // Para búsqueda en vivo
    public $selectedStudent;
    public $showModal = false;

    protected $updatesQueryString = ['search']; // Mantener búsqueda en URL si quieres

    // Reinicia la paginación al actualizar búsqueda
    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function viewDetails($studentId)
    {
        $this->selectedStudent = Student::with(['campus', 'career', 'semester', 'period'])->find($studentId);
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->selectedStudent = null;
    }

    public function approve($studentId)
    {
        $student = Student::find($studentId);
        if ($student) {
            $student->status = 'aprobado';
            $student->save();

            session()->flash('message', "El perfil de {$student->name} fue aprobado.");
        }
    }

    public function reject($studentId)
    {
        $student = Student::find($studentId);
        if ($student) {
            $student->status = 'rechazado';
            $student->save();

            session()->flash('info', "El perfil de {$student->name} fue rechazado.");
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

        // Asignar clases y etiquetas de estatus
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
        ]);
    }

}
