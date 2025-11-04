<?php

namespace App\Livewire\Students;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Student;
use App\Models\Career;
use App\Models\Period;
use App\Models\File;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\StudentsExport;

class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $careerId = null;
    public $periodId = null;

    public $careers;
    public $periods;

    protected $updatesQueryString = ['search', 'careerId', 'periodId'];

    public function mount()
    {
        $this->careers = Career::all();
        $this->periods = Period::all();
    }

    public function updatingSearch() { $this->resetPage(); }
    public function updatingCareerId() { $this->resetPage(); }
    public function updatingPeriodId() { $this->resetPage(); }

    public function render()
    {
        $query = Student::with('documents');

        if ($this->careerId) $query->where('career_id', $this->careerId);
        if ($this->periodId) $query->where('period_id', $this->periodId);
        if ($this->search) {
            $query->where(function($q){
                $q->where('name', 'like', '%'.$this->search.'%')
                  ->orWhere('last_name_paterno', 'like', '%'.$this->search.'%')
                  ->orWhere('last_name_materno', 'like', '%'.$this->search.'%')
                  ->orWhere('control_number', 'like', '%'.$this->search.'%');
            });
        }

        $students = $query->orderBy('name')->paginate(10);

        $students->getCollection()->transform(function($student){
            $student->delivered = $student->documents->whereNotNull('student_file_path')->count();
            $student->total = File::where('period_id', $student->period_id)->count();
            return $student;
        });

        return view('livewire.students.index', [
            'students' => $students,
            'pagination' => $students,
        ]);
    }

    // ================= Exportar Excel =================
    public function exportExcel()
    {
        $date = now()->format('Y-m-d'); // fecha y hora actuales
        $fileName = "estudiantes_{$date}.xlsx"; // nombre dinámico
        return Excel::download(new StudentsExport($this->careerId, $this->periodId, $this->search), $fileName);
    }

}
