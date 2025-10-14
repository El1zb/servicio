<?php

namespace App\Livewire\Careers;

use Livewire\Component;
use App\Models\Career;
use Livewire\WithPagination;

class Crud extends Component
{
    use WithPagination;

    public $search = '';
    public $name, $careerId;
    public $isOpen = false;

    protected $rules = [
        'name' => 'required|string|min:3|unique:careers,name',
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $careers = Career::query()
            ->where('name', 'like', "%{$this->search}%")
            ->orderBy('id', 'desc')
            ->paginate(10);

        return view('livewire.careers.crud', [
            'careers' => $careers
        ]);
    }

    public function create()
    {
        $this->resetInput();
        $this->isOpen = true;
    }

    public function edit(Career $career)
    {
        $this->careerId = $career->id;
        $this->name = $career->name;
        $this->isOpen = true;

        $this->rules['name'] = 'required|string|min:3|unique:careers,name,' . $this->careerId;
    }

    public function save()
    {
        $this->validate();

        Career::updateOrCreate(
            ['id' => $this->careerId],
            ['name' => $this->name]
        );

        session()->flash('message',
            $this->careerId ? 'Carrera actualizada correctamente.' : 'Carrera creada correctamente.'
        );

        $this->closeModal();
        $this->resetInput();
    }

    public function delete(Career $career)
    {
        $career->delete();
        session()->flash('message', 'Carrera eliminada correctamente.');
    }

    private function resetInput()
    {
        $this->careerId = null;
        $this->name = '';
    }

    public function closeModal()
    {
        $this->isOpen = false;
    }
}
