<?php

namespace App\Livewire\Careers;

use App\Livewire\Careers\Concerns\ManagesCareers;
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
            ->where('name', 'like', "%{$this->search}%")
            ->orderBy('id', 'desc')
            ->paginate(10);

        return view('livewire.careers.index', [
            'careers' => $careers,
        ]);
    }
}