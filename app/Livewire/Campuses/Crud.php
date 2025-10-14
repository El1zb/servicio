<?php

namespace App\Livewire\Campuses;

use Livewire\Component;
use App\Models\Campus;
use Livewire\WithPagination;

class Crud extends Component
{
    use WithPagination;

    public $search = '';
    public $name, $campusId;
    public $isOpen = false;

    protected $rules = [
        'name' => 'required|string|min:3|unique:campuses,name',
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $campuses = Campus::query()
            ->where('name', 'like', "%{$this->search}%")
            ->orderBy('id', 'desc')
            ->paginate(10);

        return view('livewire.campuses.crud', [
            'campuses' => $campuses
        ]);
    }

    public function create()
    {
        $this->resetInput();
        $this->isOpen = true;
    }

    public function edit(Campus $campus)
    {
        $this->campusId = $campus->id;
        $this->name = $campus->name;
        $this->isOpen = true;

        $this->rules['name'] = 'required|string|min:3|unique:campuses,name,' . $this->campusId;
    }

    public function save()
    {
        $this->validate();

        Campus::updateOrCreate(
            ['id' => $this->campusId],
            ['name' => $this->name]
        );

        session()->flash('message',
            $this->campusId ? 'Campus actualizado correctamente.' : 'Campus creado correctamente.'
        );

        $this->closeModal();
        $this->resetInput();
    }

    public function delete(Campus $campus)
    {
        $campus->delete();
        session()->flash('message', 'Campus eliminado correctamente.');
    }

    private function resetInput()
    {
        $this->campusId = null;
        $this->name = '';
    }

    public function closeModal()
    {
        $this->isOpen = false;
    }
}
