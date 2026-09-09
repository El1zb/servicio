<?php

namespace App\Livewire\Admin\Campuses;

use App\Livewire\Admin\Campuses\Concerns\ManagesCampuses;
use App\Models\Campus;
use App\Models\Career;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;
    use ManagesCampuses;

    protected $paginationTheme = 'tailwind';

    public array $statusFilterLabels = ['all' => 'Todos', 'active' => 'Activos', 'inactive' => 'Inactivos'];

    #[Computed]
    public function canDeleteCampus(): bool
    {
        if (! $this->campusId) {
            return false;
        }

        return Campus::withCount('students')->find($this->campusId)?->students_count === 0;
    }

    public function render()
    {
        $campuses = Campus::query()
            ->withCount('careers')
            ->where('name', 'like', "%{$this->search}%")
            ->when($this->statusFilter === 'active', fn ($q) => $q->where('is_active', true))
            ->when($this->statusFilter === 'inactive', fn ($q) => $q->where('is_active', false))
            ->orderBy('id', 'desc')
            ->paginate(50);

        return view('livewire.admin.campuses.index', [
            'campuses'       => $campuses,
            'allCareersList' => Career::orderBy('name')->get(),
            'totalCareers'   => Career::count(),
        ]);
    }
}