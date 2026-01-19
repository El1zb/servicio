<?php

namespace App\Livewire\Dashboard;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Period;
use App\Models\Semester;
use Illuminate\Validation\Rule;

class Index extends Component
{
    use WithPagination;

    public $search = '';                 // Texto de búsqueda por nombre
    public $statusFilter = 'all';        // Filtro de estado (all, active, inactive)
    public $sortBy = 'recent';           // Orden (recent, oldest, name)

    public $isOpen = false;              // Modal crear/editar abierto
    public $isDeleteModalOpen = false;   // Modal eliminar abierto

    public $periodId = null;             // ID del periodo en edición
    public $name = '';                   // Nombre del periodo
    public $start_date = '';             // Fecha de inicio
    public $end_date = '';               // Fecha de fin
    public $selectedSemesters = [];      // Semestres seleccionados

    public $periodToDelete = null;       // ID del periodo a eliminar

    public $is_active = false;           // Estado del periodo activo/inactivo

    protected $paginationTheme = 'tailwind';

    // Resetea la pagina cuando la busqueda cambie
    public function updatingSearch() { $this->resetPage(); }
    public function updatingStatusFilter() { $this->resetPage(); }
    public function updatingSortBy() { $this->resetPage(); }

    // Abrir modal creación
    public function createPeriod()
    {
        $this->resetFields(false);
        $this->is_active = true;
        $this->isOpen = true;
    }

    // Abrir modal edición
    public function editPeriod($id)
    {
        $period = Period::with('semesters')->findOrFail($id);

        $this->periodId = $period->id;
        $this->name = $period->name;
        $this->start_date = $period->start_date;
        $this->end_date = $period->end_date;
        $this->selectedSemesters = $period->semesters->pluck('id')->toArray();

        $this->is_active = (bool) $period->is_active;

        $this->isOpen = true;
    }

    // Guardar o actualizar periodo
    public function save()
    {
        $this->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('periods', 'name')->ignore($this->periodId),
            ],
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'selectedSemesters' => 'required|array|min:1',
        ], [
            'name.required' => 'El nombre del periodo es obligatorio',
            'name.unique' => 'Ya existe un periodo con este nombre',
            'start_date.required' => 'La fecha de inicio es obligatoria',
            'end_date.required' => 'La fecha de fin es obligatoria',
            'end_date.after_or_equal' => 'La fecha de fin debe ser posterior a la fecha de inicio',
            'selectedSemesters.required' => 'Debes seleccionar al menos un semestre',
            'selectedSemesters.min' => 'Debes seleccionar al menos un semestre',
        ]);

        if ($this->periodId) {
            $period = Period::findOrFail($this->periodId);

            $period->update([
                'name' => $this->name,
                'start_date' => $this->start_date,
                'end_date' => $this->end_date,
                'is_active' => $this->is_active,
            ]);

            $toastType = 'info';
            $message = 'Periodo actualizado exitosamente';
        } else {
            $period = Period::create([
                'name' => $this->name,
                'start_date' => $this->start_date,
                'end_date' => $this->end_date,
                'is_active' => $this->is_active,
            ]);
            
            $toastType = 'success';
            $message = 'Periodo creado exitosamente';
        }

        $period->semesters()->sync($this->selectedSemesters ?? []);

        $this->isOpen = false;
        $this->resetFields(false);

        $this->dispatch('notify', type: $toastType, message: $message);
    }

    // Activar / desactivar periodo
    public function toggleActive($id)
    {
        $period = Period::findOrFail($id);
        $period->update(['is_active' => !$period->is_active]);
    }

    // Guardar el ID del periodo a eliminar y abrir modal
    public function confirmDelete($id)
    {
        $this->periodToDelete = $id;
        $this->isDeleteModalOpen = true; // Abrir modal de confirmación
    }

    // Eliminar periodo
    public function deletePeriod()
    {
        if (!$this->periodToDelete) return;

        $period = Period::with(['students.documents', 'files'])->find($this->periodToDelete);
        if (!$period) return;

        // No permitir eliminar un periodo activo
        if ($period->is_active) {
            $this->dispatch('notify', type: 'error', message: 'No puedes eliminar un periodo activo');
            $this->periodToDelete = null;
            $this->isDeleteModalOpen = false;
            return;
        }

        // 🔹 Eliminar archivos físicos de los documentos de los estudiantes
        foreach ($period->students as $student) {
            foreach ($student->documents as $doc) {
                if ($doc->student_file_path && \Storage::disk('public')->exists($doc->student_file_path)) {
                    \Storage::disk('public')->delete($doc->student_file_path);
                }
            }
        }

        // 🔹 Eliminar archivos físicos de los archivos base del periodo
        foreach ($period->files as $file) {
            if ($file->file_path && \Storage::disk('public')->exists($file->file_path)) {
                \Storage::disk('public')->delete($file->file_path);
            }
            if ($file->example_path && \Storage::disk('public')->exists($file->example_path)) {
                \Storage::disk('public')->delete($file->example_path);
            }
        }

        // 🔹 Eliminar el periodo (DB en cascada se encarga de estudiantes, documentos y files)
        $period->delete();

        $this->dispatch('notify', type: 'error', message: 'Periodo eliminado exitosamente');

        $this->periodToDelete = null;
        $this->isDeleteModalOpen = false;
    }

    // Reset de campos
    private function resetFields($closeModal = true)
    {
        $this->periodId = null;
        $this->name = '';
        $this->start_date = '';
        $this->end_date = '';
        $this->selectedSemesters = [];

        $this->is_active = false;


        $this->resetValidation();

        if ($closeModal) {
            $this->isOpen = false;
        }
    }

    public function render()
    {
        $query = Period::with(['semesters', 'students'])
            ->withCount([
                'students',
                'files',
                'students as approved_students_count' => fn($q) => $q->where('status', 'aprobado'),
                'students as pending_students_count' => fn($q) => $q->where('status', 'pendiente'),
                'students as rejected_students_count' => fn($q) => $q->where('status', 'rechazado'),
            ]);

        if ($this->search) {
            $query->where('name', 'like', '%'.$this->search.'%');
        }


        if ($this->statusFilter === 'active') {
            $query->where('is_active', true);
        } elseif ($this->statusFilter === 'inactive') {
            $query->where('is_active', false);
        }

        switch ($this->sortBy) {
            case 'oldest':
                $query->orderBy('start_date', 'asc'); // de la más antigua a la más reciente
                break;
            case 'name':
                $query->orderBy('name');
                break;
            default:
                $query->orderBy('start_date', 'desc'); // de la más reciente a la más antigua
        }

        $periods = $query->paginate(12);

        $periods->getCollection()->transform(function ($period) {
            $totalStudents = $period->students_count ?: 1;
            $period->approvalRate = round(($period->approved_students_count / $totalStudents) * 100);
            $period->startFormatted = \Carbon\Carbon::parse($period->start_date)->format('d/m/Y');
            $period->endFormatted = \Carbon\Carbon::parse($period->end_date)->format('d/m/Y');
            $period->hasStudents = $period->students_count > 0;
            return $period;
        });

        $stats = [
            'total_periods' => Period::count(),
            'active_periods' => Period::where('is_active', true)->count(),
            'total_students' => \App\Models\Student::count(),
            'pending_approvals' => \App\Models\Student::where('status', 'pendiente')->count(),
        ];

        return view('livewire.dashboard.index', [
            'periods' => $periods,
            'semesters' => Semester::where('is_active', true)->get(),
            'stats' => $stats,
        ]);
    }
}
