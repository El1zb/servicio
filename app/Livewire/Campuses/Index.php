<?php

namespace App\Livewire\Campuses;

use App\Livewire\Campuses\Concerns\ManagesCampuses;
use App\Models\Campus;
use App\Models\Career;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;
    use ManagesCampuses;

    protected $paginationTheme = 'tailwind';

    public function render()
    {
        $campuses = Campus::query()
            ->withCount('careers')
            ->where('name', 'like', "%{$this->search}%")
            ->when($this->statusFilter === 'active', fn ($q) => $q->where('is_active', true))
            ->when($this->statusFilter === 'inactive', fn ($q) => $q->where('is_active', false))
            ->orderBy('id', 'desc')
            ->paginate(50);

        return view('livewire.campuses.index', [
            'campuses'       => $campuses,
            'allCareersList' => Career::orderBy('name')->get(),
            'totalCareers'   => Career::count(),
        ]);
    }
}