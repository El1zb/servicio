<?php

namespace App\Livewire\Campuses\Concerns;

use App\Models\Campus;
use Illuminate\Validation\Rule;

trait ManagesCampuses
{
    // ─── Propiedades ────────────────────────────────────────────────────────────

    public string  $search           = '';
    public ?int    $campusId         = null;
    public string  $name             = '';
    public bool    $isOpen           = false;
    public bool    $isDeleteModalOpen = false;
    public ?int    $campusToDelete   = null;

    // ─── Watcher de paginación ───────────────────────────────────────────────────

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    // ─── Abrir modal creación ────────────────────────────────────────────────────

    public function create(): void
    {
        $this->resetCampusInput();
        $this->isOpen = true;
    }

    // ─── Abrir modal edición ─────────────────────────────────────────────────────

    public function edit(Campus $campus): void
    {
        $this->campusId = $campus->id;
        $this->name     = $campus->name;
        $this->isOpen   = true;
    }

    // ─── Guardar / actualizar ────────────────────────────────────────────────────

    public function save(): void
    {
        $this->validate(
            [
                'name' => [
                    'required', 'string', 'min:3',
                    Rule::unique('campuses', 'name')->ignore($this->campusId),
                ],
            ],
            [
                'name.required' => 'El nombre del campus es obligatorio.',
                'name.string'   => 'El nombre debe ser un texto válido.',
                'name.min'      => 'El nombre del campus debe tener al menos 3 caracteres.',
                'name.unique'   => 'Ya existe un campus con este nombre. Por favor elige otro.',
            ]
        );

        Campus::updateOrCreate(
            ['id' => $this->campusId],
            ['name' => $this->name]
        );

        $type    = $this->campusId ? 'info' : 'success';
        $action  = $this->campusId ? 'actualizado' : 'creado';

        $this->dispatch('notify', type: $type, message: "Campus {$action} correctamente");

        $this->closeModal();
    }

    // ─── Confirmar eliminación ───────────────────────────────────────────────────

    public function confirmDelete(int $id): void
    {
        $this->campusToDelete    = $id;
        $this->isDeleteModalOpen = true;
    }

    // ─── Eliminar campus ─────────────────────────────────────────────────────────

    public function deleteCampus(): void
    {
        if (! $this->campusToDelete) return;

        $campus = Campus::find($this->campusToDelete);

        if (! $campus) return;

        $campus->delete();

        $this->dispatch('notify', type: 'error', message: 'Campus eliminado correctamente');

        $this->campusToDelete    = null;
        $this->isDeleteModalOpen = false;
    }

    // ─── Cerrar modal ────────────────────────────────────────────────────────────

    public function closeModal(): void
    {
        $this->isOpen = false;
        $this->resetCampusInput();
    }

    // ─── Reset de campos ─────────────────────────────────────────────────────────

    private function resetCampusInput(): void
    {
        $this->campusId = null;
        $this->name     = '';
        $this->resetValidation();
    }
}