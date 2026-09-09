<?php

namespace App\Livewire\Admin\Careers;

use App\Livewire\Admin\Careers\Concerns\ManagesCareers;
use App\Models\Campus;
use App\Models\Career;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;
    use ManagesCareers;

    protected $paginationTheme = 'tailwind';

    public array $statusFilterLabels = ['all' => 'Todos', 'active' => 'Activos', 'inactive' => 'Inactivos'];

    #[Computed]
    public function canDeleteCareer(): bool
    {
        if (! $this->careerId) {
            return false;
        }

        return Career::withCount('students')->find($this->careerId)?->students_count === 0;
    }

    public function render()
    {
        $careers = Career::query()
            ->withCount('campuses')
            ->where('name', 'like', "%{$this->search}%")
            ->when($this->statusFilter === 'active', fn ($q) => $q->where('is_active', true))
            ->when($this->statusFilter === 'inactive', fn ($q) => $q->where('is_active', false))
            ->orderBy('id', 'desc')
            ->paginate(50);

        return view('livewire.admin.careers.index', [
            'careers'         => $careers,
            'allCampusesList' => Campus::orderBy('name')->get(),
            'totalCampuses'   => Campus::count(),
        ]);
    }
}