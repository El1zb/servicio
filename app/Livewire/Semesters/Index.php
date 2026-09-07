<?php

namespace App\Livewire\Semesters;

use App\Livewire\Semesters\Concerns\ManagesSemesters;
use App\Models\Semester;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;
    use ManagesSemesters;

    protected $paginationTheme = 'tailwind';

    public function render()
    {
        $semesters = Semester::query()
            ->where('name', 'like', "%{$this->search}%")
            ->when($this->statusFilter === 'active', fn ($q) => $q->where('is_active', true))
            ->when($this->statusFilter === 'inactive', fn ($q) => $q->where('is_active', false))
            ->orderBy('id', 'desc')
            ->paginate(50);

        return view('livewire.semesters.index', [
            'semesters' => $semesters,
        ]);
    }
}