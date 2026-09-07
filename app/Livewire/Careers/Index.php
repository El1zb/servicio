<?php

namespace App\Livewire\Careers;

use App\Livewire\Careers\Concerns\ManagesCareers;
use App\Models\Campus;
use App\Models\Career;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;
    use ManagesCareers;

    protected $paginationTheme = 'tailwind';

    public function render()
    {
        $careers = Career::query()
            ->withCount('campuses')
            ->where('name', 'like', "%{$this->search}%")
            ->when($this->statusFilter === 'active', fn ($q) => $q->where('is_active', true))
            ->when($this->statusFilter === 'inactive', fn ($q) => $q->where('is_active', false))
            ->orderBy('id', 'desc')
            ->paginate(50);

        return view('livewire.careers.index', [
            'careers'         => $careers,
            'allCampusesList' => Campus::orderBy('name')->get(),
            'totalCampuses'   => Campus::count(),
        ]);
    }
}