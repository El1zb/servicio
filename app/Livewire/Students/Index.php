<?php

namespace App\Livewire\Students;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Student;
use App\Models\Career;
use App\Models\Period;
use App\Models\File;

class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $careerId = null;
    public $periodId = null;

    public $careers;
    public $periods;

    protected $updatesQueryString = ['search', 'careerId', 'periodId'];

    public $studentsPrepared = [];

    public function mount()
    {
        $this->careers = Career::all();
        $this->periods = Period::all();
    }

    // Resetear la paginación cuando cambian filtros o búsqueda
    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingCareerId()
    {
        $this->resetPage();
    }

    public function updatingPeriodId()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = Student::with('documents');

        // 🔹 Filtrar por carrera
        if ($this->careerId) {
            $query->where('career_id', $this->careerId);
        }

        // 🔹 Filtrar por periodo
        if ($this->periodId) {
            $query->where('period_id', $this->periodId);
        }

        // 🔹 Búsqueda dentro de los resultados filtrados
        if ($this->search) {
            $query->where(function($q) {
                $q->where('name', 'like', '%'.$this->search.'%')
                ->orWhere('last_name_paterno', 'like', '%'.$this->search.'%')
                ->orWhere('last_name_materno', 'like', '%'.$this->search.'%')
                ->orWhere('control_number', 'like', '%'.$this->search.'%');
            });
        }

        // 🔹 Ordenar por nombre y paginar
        $students = $query->orderBy('name')->paginate(10);

        // 🔹 Agregar campos dinámicos para la tabla
        $students->getCollection()->transform(function ($student) {
            $student->delivered = $student->documents->whereNotNull('student_file_path')->count();
            $student->total = File::where('period_id', $student->period_id)->count();
            return $student;
        });

        return view('livewire.students.index', [
            'students' => $students, // objetos Eloquent con propiedades dinámicas
            'pagination' => $students,
        ]);
    }

}
