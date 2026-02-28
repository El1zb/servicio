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
            ->orderBy('id', 'desc')
            ->paginate(10);

        return view('livewire.semesters.index', [
            'semesters' => $semesters,
        ]);
    }
}