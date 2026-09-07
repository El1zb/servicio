<?php

namespace App\Livewire\Dashboard;

use App\Livewire\Dashboard\Index\Concerns\ManagesPeriods;
use App\Livewire\Dashboard\Index\Concerns\ManagesStats;
use App\Models\Semester;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;
    use ManagesPeriods;
    use ManagesStats;

    protected $paginationTheme = 'tailwind';

    public function render()
    {
        return view('livewire.dashboard.index', [
            'periods'   => $this->getPeriodsQuery(),
            'semesters' => Semester::where('is_active', true)->get(),
        ]);
    }
}