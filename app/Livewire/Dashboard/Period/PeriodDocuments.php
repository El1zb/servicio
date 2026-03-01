<?php

namespace App\Livewire\Dashboard\Period;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use App\Livewire\Dashboard\Period\Concerns\ManagesDocuments;
use App\Models\Period;

class PeriodDocuments extends Component
{
    use WithPagination, WithFileUploads, ManagesDocuments;

    public int $periodId;
    public Period $period;
    public string $start_date = '';
    public string $end_date   = '';

    public function mount(int $id): void
    {
        $this->periodId = $id;
        $this->loadPeriod();
    }

    public function loadPeriod(): void
    {
        $this->period     = Period::with(['files'])->findOrFail($this->periodId);
        $this->start_date = $this->period->start_date;
        $this->end_date   = $this->period->end_date;
    }

    public function goBack()
    {
        return redirect()->route('dashboard');
    }

    public function render()
    {
        return view('livewire.dashboard.period.partials.documents-tab', [
            'paginatedFiles' => $this->getFilesPaginated(),
        ])->layout('components.layouts.period-detail');
    }
}