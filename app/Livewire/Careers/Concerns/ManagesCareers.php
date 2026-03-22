<?php

namespace App\Livewire\Careers\Concerns;

use App\Models\Career;
use Illuminate\Validation\Rule;

trait ManagesCareers
{
    // ─── Propiedades ────────────────────────────────────────────────────────────

    public string $search           = '';
    public ?int   $careerId         = null;
    public string $name             = '';
    public bool   $isOpen           = false;
    public bool   $isDeleteModalOpen = false;
    public ?int   $careerToDelete   = null;

    // ─── Watcher de paginación ───────────────────────────────────────────────────

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    // ─── Abrir modal creación ────────────────────────────────────────────────────

    public function create(): void
    {
        $this->resetCareerInput();
        $this->isOpen = true;
    }

    // ─── Abrir modal edición ─────────────────────────────────────────────────────

    public function edit(Career $career): void
    {
        $this->careerId = $career->id;
        $this->name     = $career->name;
        $this->isOpen   = true;
    }

    // ─── Guardar / actualizar ────────────────────────────────────────────────────

    public function save(): void
    {
        $this->validate(
            [
                'name' => [
                    'required', 'string', 'min:3',
                    Rule::unique('careers', 'name')->ignore($this->careerId)->whereNull('deleted_at'),
                ],
            ],
            [
                'name.required' => 'Debes ingresar el nombre de la carrera.',
                'name.min'      => 'El nombre de la carrera debe tener al menos 3 caracteres.',
                'name.unique'   => 'La carrera ya existe, por favor elige otro nombre.',
            ]
        );

        Career::updateOrCreate(
            ['id' => $this->careerId],
            ['name' => $this->name]
        );

        $type   = $this->careerId ? 'info' : 'success';
        $action = $this->careerId ? 'actualizada' : 'creada';

        $this->dispatch('notify', type: $type, message: "Carrera {$action} correctamente");

        $this->closeModal();
    }

    // ─── Confirmar eliminación ───────────────────────────────────────────────────

    public function confirmDelete(int $id): void
    {
        $this->careerToDelete    = $id;
        $this->isDeleteModalOpen = true;
    }

    // ─── Eliminar carrera ────────────────────────────────────────────────────────

    public function deleteCareer(): void
    {
        if (! $this->careerToDelete) return;

        $career = Career::find($this->careerToDelete);

        if (! $career) return;

        $career->delete();

        $this->dispatch('notify', type: 'error', message: 'Carrera eliminada correctamente');

        $this->careerToDelete    = null;
        $this->isDeleteModalOpen = false;
    }

    // ─── Cerrar modal ────────────────────────────────────────────────────────────

    public function closeModal(): void
    {
        $this->isOpen = false;
        $this->resetCareerInput();
    }

    // ─── Reset de campos ─────────────────────────────────────────────────────────

    private function resetCareerInput(): void
    {
        $this->careerId = null;
        $this->name     = '';
        $this->resetValidation();
    }
}