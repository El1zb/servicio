<?php

namespace App\Livewire\Semesters\Concerns;

use App\Models\Semester;
use Illuminate\Validation\Rule;

trait ManagesSemesters
{
    // ─── Propiedades ────────────────────────────────────────────────────────────

    public string $search            = '';
    public string $statusFilter      = 'all';
    public ?int   $semesterId        = null;
    public string $name              = '';
    public bool   $is_active         = true;
    public bool   $isOpen            = false;
    public bool   $isDeleteModalOpen = false;
    public ?int   $semesterToDelete  = null;
    public int    $formInstance      = 0;

    // ─── Watcher de paginación ───────────────────────────────────────────────────

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function setStatusFilter(string $value): void
    {
        $this->statusFilter = $value;
        $this->resetPage();
    }

    // ─── Limpiar errores por campo ───────────────────────────────────────────────

    public function updated($propertyName): void
    {
        $this->resetValidation($propertyName);
    }

    // ─── Abrir modal creación ────────────────────────────────────────────────────

    public function create(): void
    {
        $this->resetSemesterInput();
        $this->formInstance++;
        $this->isOpen = true;
    }

    // ─── Abrir modal edición ─────────────────────────────────────────────────────

    public function edit(Semester $semester): void
    {
        $this->semesterId = $semester->id;
        $this->name       = $semester->name;
        $this->is_active  = (bool) $semester->is_active;
        $this->resetValidation();
        $this->formInstance++;
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
        $this->isOpen            = false;
        $this->semesterToDelete  = $id;
        $this->isDeleteModalOpen = true;
    }

    // ─── Eliminar semestre ───────────────────────────────────────────────────────

    public function deleteSemester(): void
    {
        if (! $this->semesterToDelete) return;

        $semester = Semester::withCount('students')->find($this->semesterToDelete);

        if (! $semester) return;

        if ($semester->students_count > 0) {
            $this->dispatch('notify', type: 'warning', message: 'No puedes eliminar un semestre con estudiantes registrados. Desactívalo en su lugar.');
            $this->isDeleteModalOpen = false;
            return;
        }

        $semester->delete();

        $this->dispatch('notify', type: 'error', message: 'Semestre eliminado correctamente');

        $this->semesterToDelete  = null;
        $this->isDeleteModalOpen = false;
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