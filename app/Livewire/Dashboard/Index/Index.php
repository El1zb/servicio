<?php

namespace App\Livewire\Dashboard\Index;

use Livewire\Component;
use Livewire\WithPagination;

use App\Models\Period;
use App\Models\Semester;

use App\Livewire\Dashboard\Index\Concerns\ManagesPeriods;
use App\Livewire\Dashboard\Index\Concerns\ManagesFilters;

class Index extends Component
{
    use WithPagination, ManagesPeriods, ManagesFilters;

    protected $paginationTheme = 'tailwind';

    // ── Modal state ────────────────────────────────────────────────
    public bool $isOpen            = false;
    public bool $isDeleteModalOpen = false;

    // ── Form fields ────────────────────────────────────────────────
    public ?int   $periodId          = null;
    public string $name              = '';
    public string $start_date        = '';
    public string $end_date          = '';
    public array  $selectedSemesters = [];
    public bool   $is_active         = false;

    // ── Delete target ──────────────────────────────────────────────
    public ?int $periodToDelete = null;

    // ──────────────────────────────────────────────────────────────
    public function render()
    {
        return view('livewire.dashboard.index.index', [
            'periods'   => $this->getPeriodsQuery()->paginate(12),
            'semesters' => Semester::where('is_active', true)->get(),
            'stats'     => $this->getStats(),
        ]);
    }
}