<?php

namespace App\Livewire\Admin\Semesters;

use App\Livewire\Admin\Semesters\Concerns\ManagesSemesters;
use App\Models\Semester;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;
    use ManagesSemesters;

    protected $paginationTheme = 'tailwind';

    public array $statusFilterLabels = ['all' => 'Todos', 'active' => 'Activos', 'inactive' => 'Inactivos'];

    #[Computed]
    public function canDeleteSemester(): bool
    {
        if (! $this->semesterId) {
            return false;
        }

        return Semester::withCount('students')->find($this->semesterId)?->students_count === 0;
    }

    public function render()
    {
        $semesters = Semester::query()
            ->where('name', 'like', "%{$this->search}%")
            ->when($this->statusFilter === 'active', fn ($q) => $q->where('is_active', true))
            ->when($this->statusFilter === 'inactive', fn ($q) => $q->where('is_active', false))
            ->orderBy('id', 'desc')
            ->paginate(50);

        return view('livewire.admin.semesters.index', [
            'semesters' => $semesters,
        ]);
    }
}