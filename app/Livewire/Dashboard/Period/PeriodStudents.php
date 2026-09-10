<?php

namespace App\Livewire\Dashboard\Period;

use Livewire\Component;
use Livewire\WithPagination;
use App\Livewire\Dashboard\Period\Concerns\ManagesStudents;
use App\Models\Period;
use App\Models\Campus;
use App\Models\Career;
use App\Models\Semester;
use Illuminate\Support\Facades\Cache;

class PeriodStudents extends Component
{
    use WithPagination, ManagesStudents;

    public int $periodId;
    public Period $period;
    public string $start_date = '';
    public string $end_date   = '';

    // Necesarias para preview-modal
    public ?string $previewPath = null;
    public ?string $previewName = null;

    public function previewFile(string $path, string $name): void
    {
        $this->previewPath = $path;
        $this->previewName = $name;
    }

    public function mount(int $id): void
    {
        $this->periodId = $id;
        $this->loadPeriod();
    }

    public function loadPeriod(): void
    {
        $this->period     = Period::with(['semesters', 'files'])->findOrFail($this->periodId);
        $this->start_date = $this->period->start_date;
        $this->end_date   = $this->period->end_date;
    }

    public function goBack()
    {
        return redirect()->route('periods');
    }

    public function render()
    {
        return view('livewire.dashboard.period.partials.students-tab', [
            'period'          => $this->period,
            'students'        => $this->getStudentsPaginated(),
            // Catálogos casi estáticos (solo cambian por CRUD de admin en
            // Campuses/Careers/Semesters) — cacheados 1h en vez de traer la
            // tabla completa en cada búsqueda/paginación/acción de esta
            // pestaña, que es de las más usadas por el admin.
            'campuses'        => Cache::remember('catalog:campuses', 3600, fn () => Campus::all()),
            // Listas completas: para el formulario de edición (se puede
            // asignar cualquier carrera/semestre del sistema).
            'careers'         => Cache::remember('catalog:careers', 3600, fn () => Career::all()),
            'semesters'       => Cache::remember('catalog:semesters', 3600, fn () => Semester::all()),
            // Filtro de carrera: acotado a lo que de verdad tienen los
            // estudiantes de este periodo.
            'filterCareers'   => $this->getFilterCareers(),
            // Filtro de semestre: los semestres configurados para el
            // periodo (no los de los estudiantes; el periodo define cuáles
            // aplican, aunque algún estudiante tenga uno fuera de esa
            // configuración).
            'filterSemesters' => $this->period->semesters,
            'pendingCount'    => $this->pendingCount(),
            'stats'           => $this->getPeriodStats(),
        ])->layout('components.layouts.period-detail');
    }
}