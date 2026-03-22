<?php

namespace App\Livewire\Semesters\Concerns;

use App\Models\Semester;
use Illuminate\Validation\Rule;

trait ManagesSemesters
{
    // ─── Propiedades ────────────────────────────────────────────────────────────

    public string $search            = '';
    public ?int   $semesterId        = null;
    public string $name              = '';
    public bool   $is_active         = true;
    public bool   $isOpen            = false;
    public bool   $isDeleteModalOpen = false;
    public ?int   $semesterToDelete  = null;

    // ─── Watcher de paginación ───────────────────────────────────────────────────

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    // ─── Abrir modal creación ────────────────────────────────────────────────────

    public function create(): void
    {
        $this->resetSemesterInput();
        $this->isOpen = true;
    }

    // ─── Abrir modal edición ─────────────────────────────────────────────────────

    public function edit(Semester $semester): void
    {
        $this->semesterId = $semester->id;
        $this->name       = $semester->name;
        $this->is_active  = (bool) $semester->is_active;
        $this->isOpen     = true;
    }

    // ─── Guardar / actualizar ────────────────────────────────────────────────────

    public function save(): void
    {
        $this->validate(
            [
                'name' => [
                    'required', 'string', 'min:1',
                    Rule::unique('semesters', 'name')->ignore($this->semesterId)->whereNull('deleted_at'),
                ],
                'is_active' => 'boolean',
            ],
            [
                'name.required' => 'El nombre del semestre es obligatorio.',
                'name.string'   => 'El nombre debe ser un texto válido.',
                'name.min'      => 'El nombre del semestre debe tener al menos 1 carácter.',
                'name.unique'   => 'Ya existe un semestre con este nombre. Por favor elige otro.',
            ]
        );

        Semester::updateOrCreate(
            ['id' => $this->semesterId],
            ['name' => $this->name, 'is_active' => $this->is_active]
        );

        $type   = $this->semesterId ? 'info' : 'success';
        $action = $this->semesterId ? 'actualizado' : 'creado';

        $this->dispatch('notify', type: $type, message: "Semestre {$action} correctamente");

        $this->closeModal();
    }

    // ─── Confirmar eliminación ───────────────────────────────────────────────────

    public function confirmDelete(int $id): void
    {
        $this->semesterToDelete  = $id;
        $this->isDeleteModalOpen = true;
    }

    // ─── Eliminar semestre ───────────────────────────────────────────────────────

    public function deleteSemester(): void
    {
        if (! $this->semesterToDelete) return;

        $semester = Semester::find($this->semesterToDelete);

        if (! $semester) return;

        $semester->delete();

        $this->dispatch('notify', type: 'error', message: 'Semestre eliminado correctamente');

        $this->semesterToDelete  = null;
        $this->isDeleteModalOpen = false;
    }

    // ─── Activar / desactivar ────────────────────────────────────────────────────

    public function toggleActive(int $id): void
    {
        $semester            = Semester::findOrFail($id);
        $semester->is_active = ! $semester->is_active;
        $semester->save();

        session()->flash('message', 'Visibilidad actualizada correctamente.');
    }

    // ─── Cerrar modal ────────────────────────────────────────────────────────────

    public function closeModal(): void
    {
        $this->isOpen = false;
        $this->resetSemesterInput();
    }

    // ─── Reset de campos ─────────────────────────────────────────────────────────

    private function resetSemesterInput(): void
    {
        $this->semesterId = null;
        $this->name       = '';
        $this->is_active  = true;
        $this->resetValidation();
    }
}