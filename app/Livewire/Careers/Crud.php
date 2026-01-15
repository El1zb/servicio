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
    public $isDeleteModalOpen = false; // Modal de confirmación
    public $careerToDelete = null; // Carrera a eliminar

    protected $rules = [
        'name' => 'required|string|min:3',
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

    // Abrir modal de creación
    public function create()
    {
        $this->resetInput();
        $this->isOpen = true;
    }

    // Abrir modal de edición
    public function edit(Career $career)
    {
        $this->careerId = $career->id;
        $this->name = $career->name;
        $this->isOpen = true;
    }

    // Guardar o actualizar
    public function save()
    {
        $rules = [
            'name' => 'required|string|min:3'
        ];

        if ($this->careerId) {
            // Ignorar el registro actual si estamos editando
            $rules['name'] .= '|unique:careers,name,' . $this->careerId . ',id';
        } else {
            $rules['name'] .= '|unique:careers,name';
        }

        // Mensajes personalizados
        $messages = [
            'name.required' => 'Debes ingresar el nombre de la carrera.',
            'name.min' => 'El nombre de la carrera debe tener al menos 3 caracteres.',
            'name.unique' => "La carrera ya existe, por favor elige otro nombre.",
        ];

        $this->validate($rules, $messages);

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

    // Guardar el ID de la carrera a eliminar y abrir modal
    public function confirmDelete($id)
    {
        $this->careerToDelete = $id;
        $this->isDeleteModalOpen = true;
    }

    // Eliminar carrera
    public function deleteCareer()
    {
        if (!$this->careerToDelete) return;

        $career = Career::find($this->careerToDelete);
        if (!$career) return;

        $career->delete();

        session()->flash('message', 'Carrera eliminada correctamente.');

        $this->careerToDelete = null;
        $this->isDeleteModalOpen = false;
    }

    private function resetInput()
    {
        $this->careerId = null;
        $this->name = '';
        $this->resetValidation();
    }

    public function closeModal()
    {
        $this->isOpen = false;
        $this->resetInput();
    }
}
