<?php

namespace App\Livewire\Campuses;

use App\Livewire\Campuses\Concerns\ManagesCampuses;
use App\Models\Campus;
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
            ->where('name', 'like', "%{$this->search}%")
            ->orderBy('id', 'desc')
            ->paginate(10);

        return view('livewire.campuses.index', [
            'campuses' => $campuses,
        ]);
    }
}