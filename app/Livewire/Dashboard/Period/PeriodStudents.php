<?php

namespace App\Livewire\Dashboard\Period;

use Livewire\Component;
use Livewire\WithPagination;
use App\Livewire\Dashboard\Period\Concerns\ManagesStudents;
use App\Models\Period;
use App\Models\Campus;
use App\Models\Career;

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
        return redirect()->route('dashboard');
    }

    public function render()
    {
        return view('livewire.dashboard.period.partials.students-tab', [
            'students'  => $this->getStudentsPaginated(),
            'campuses'  => Campus::all(),
            'careers'   => Career::all(),
            'semesters' => $this->period->semesters,
        ])->layout('components.layouts.period-detail');
    }
}