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
    public $isDeleteModalOpen = false; // Modal de confirmación
    public $campusToDelete = null; // Campus a eliminar

    protected $rules = [
        'name' => 'required|string|min:3',
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

    // Abrir modal de creación
    public function create()
    {
        $this->resetInput();
        $this->isOpen = true;
    }

    // Abrir modal de edición
    public function edit(Campus $campus)
    {
        $this->campusId = $campus->id;
        $this->name = $campus->name;
        $this->isOpen = true;
    }

    // Guardar o actualizar
    public function save()
    {
        $rules = [
            'name' => 'required|string|min:3'
        ];

        if ($this->campusId) {
            // Ignorar el registro actual si estamos editando
            $rules['name'] .= '|unique:campuses,name,' . $this->campusId . ',id';
        } else {
            $rules['name'] .= '|unique:campuses,name';
        }

        // Mensajes personalizados
        $messages = [
            'name.required' => 'El nombre del campus es obligatorio.',
            'name.string'   => 'El nombre debe ser un texto válido.',
            'name.min'      => 'El nombre del campus debe tener al menos 3 caracteres.',
            'name.unique'   => 'Ya existe un campus con este nombre. Por favor elige otro.'
        ];

        $this->validate($rules, $messages);

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


    // Guardar el ID del campus a eliminar y abrir modal
    public function confirmDelete($id)
    {
        $this->campusToDelete = $id;
        $this->isDeleteModalOpen = true;
    }

    // Eliminar campus
    public function deleteCampus()
    {
        if (!$this->campusToDelete) return;

        $campus = Campus::find($this->campusToDelete);
        if (!$campus) return;

        $campus->delete();

        session()->flash('message', 'Campus eliminado correctamente.');

        $this->campusToDelete = null;
        $this->isDeleteModalOpen = false;
    }

    private function resetInput()
    {
        $this->campusId = null;
        $this->name = '';
        $this->resetValidation();
    }

    public function closeModal()
    {
        $this->isOpen = false;
        $this->resetInput();
    }
}
