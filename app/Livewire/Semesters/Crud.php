<?php

namespace App\Livewire\Semesters;

use Livewire\Component;
use App\Models\Semester;
use Livewire\WithPagination;

class Crud extends Component
{
    use WithPagination;

    public $search = '';
    public $name, $semesterId, $is_active = true;
    public $isOpen = false;
    public $isDeleteModalOpen = false; // Modal de confirmación
    public $semesterToDelete = null;    // Semestre a eliminar

    protected $rules = [
        'name' => 'required|string|min:1',
        'is_active' => 'boolean',
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $semesters = Semester::query()
            ->where('name', 'like', "%{$this->search}%")
            ->orderBy('id', 'desc')
            ->paginate(10);

        return view('livewire.semesters.crud', [
            'semesters' => $semesters
        ]);
    }

    // Abrir modal de creación
    public function create()
    {
        $this->resetInput();
        $this->isOpen = true;
    }

    // Abrir modal de edición
    public function edit(Semester $semester)
    {
        $this->semesterId = $semester->id;
        $this->name = $semester->name;
        $this->is_active = $semester->is_active;
        $this->isOpen = true;
    }

    // Guardar o actualizar
    public function save()
    {
        $rules = [
            'name' => 'required|string|min:1',
            'is_active' => 'boolean',
        ];

        if ($this->semesterId) {
            $rules['name'] .= '|unique:semesters,name,' . $this->semesterId . ',id';
        } else {
            $rules['name'] .= '|unique:semesters,name';
        }

        // Mensajes personalizados
        $messages = [
            'name.required' => 'El nombre del semestre es obligatorio.',
            'name.string'   => 'El nombre debe ser un texto válido.',
            'name.min'      => 'El nombre del semestre debe tener al menos 1 carácter.',
            'name.unique'   => 'Ya existe un semestre con este nombre. Por favor elige otro.',
        ];

        $this->validate($rules, $messages);

        Semester::updateOrCreate(
            ['id' => $this->semesterId],
            [
                'name' => $this->name,
                'is_active' => $this->is_active,
            ]
        );

        $action = $this->semesterId ? 'actualizado' : 'creado';
        $type   = $this->semesterId ? 'info' : 'success';
        $this->dispatch('notify', type: $type, message: "Semestre {$action} correctamente");


        $this->closeModal();
        $this->resetInput();
    }

    // Guardar el ID del semestre a eliminar y abrir modal
    public function confirmDelete($id)
    {
        $this->semesterToDelete = $id;
        $this->isDeleteModalOpen = true;
    }

    // Eliminar semestre
    public function deleteSemester()
    {
        if (!$this->semesterToDelete) return;

        $semester = Semester::find($this->semesterToDelete);
        if (!$semester) return;

        $semester->delete();

        $this->dispatch('notify', type: 'error', message: "Semestre eliminado correctamente");

        $this->semesterToDelete = null;
        $this->isDeleteModalOpen = false;
    }

    private function resetInput()
    {
        $this->semesterId = null;
        $this->name = '';
        $this->is_active = true;
        $this->resetValidation();
    }

    public function closeModal()
    {
        $this->isOpen = false;
        $this->resetInput();
    }

    // Activar/desactivar semestre
    public function toggleActive($id)
    {
        $semester = Semester::findOrFail($id);
        $semester->is_active = !$semester->is_active;
        $semester->save();

        session()->flash('message', 'Visibilidad actualizada correctamente.');
    }
}
