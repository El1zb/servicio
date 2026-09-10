<?php

namespace App\Livewire\Admin\Campuses\Concerns;

use App\Models\Campus;
use App\Models\Career;
use Illuminate\Validation\Rule;

trait ManagesCampuses
{
    // ─── Propiedades ────────────────────────────────────────────────────────────

    public string  $search           = '';
    public string  $statusFilter     = 'all';
    public ?int    $campusId         = null;
    public string  $name             = '';
    public bool    $allCareers       = true;
    public array   $selectedCareerIds = [];
    public bool    $is_active        = true;
    public bool    $isOpen           = false;
    public bool    $isDeleteModalOpen = false;
    public ?int    $campusToDelete   = null;
    public ?string $campusToDeleteName = null;
    public int     $formInstance     = 0;

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
        $this->resetCampusInput();
        $this->formInstance++;
        $this->isOpen = true;
    }

    // ─── Abrir modal edición ─────────────────────────────────────────────────────

    public function edit(Campus $campus): void
    {
        $this->campusId  = $campus->id;
        $this->name      = $campus->name;
        $this->is_active = (bool) $campus->is_active;

        $linkedCareerIds       = $campus->careers()->pluck('careers.id')->toArray();
        $this->allCareers      = count($linkedCareerIds) === Career::count();
        $this->selectedCareerIds = $this->allCareers ? [] : $linkedCareerIds;

        $this->resetValidation();
        $this->formInstance++;
        $this->isOpen   = true;
    }

    // ─── Guardar / actualizar ────────────────────────────────────────────────────

    public function save(): void
    {
        $this->validate(
            [
                'name' => [
                    'required', 'string', 'min:3',
                    Rule::unique('campuses', 'name')->ignore($this->campusId)->whereNull('deleted_at'),
                ],
            ],
            [
                'name.required' => 'El nombre del campus es obligatorio.',
                'name.string'   => 'El nombre debe ser un texto válido.',
                'name.min'      => 'El nombre del campus debe tener al menos 3 caracteres.',
                'name.unique'   => 'Ya existe un campus con este nombre. Por favor elige otro.',
            ]
        );

        $campus = Campus::updateOrCreate(
            ['id' => $this->campusId],
            ['name' => $this->name, 'is_active' => $this->is_active]
        );

        $campus->careers()->sync($this->allCareers ? Career::pluck('id') : $this->selectedCareerIds);

        $type    = $this->campusId ? 'info' : 'success';
        $action  = $this->campusId ? 'actualizado' : 'creado';

        $this->dispatch('notify', type: $type, message: "Campus {$action} correctamente");

        $this->closeModal();
    }

    // ─── Confirmar eliminación ───────────────────────────────────────────────────

    public function confirmDelete(int $id): void
    {
        $this->isOpen              = false;
        $this->campusToDelete      = $id;
        $this->campusToDeleteName  = Campus::find($id)?->name;
        $this->isDeleteModalOpen   = true;
    }

    // ─── Eliminar campus ─────────────────────────────────────────────────────────

    public function deleteCampus(): void
    {
        if (! $this->campusToDelete) return;

        $campus = Campus::withCount('students')->find($this->campusToDelete);

        if (! $campus) return;

        if ($campus->students_count > 0) {
            $this->dispatch('notify', type: 'warning', message: 'No puedes eliminar un campus con estudiantes registrados. Desactívalo en su lugar.');
            $this->isDeleteModalOpen = false;
            return;
        }

        $campus->delete();

        $this->dispatch('notify', type: 'error', message: 'Campus eliminado correctamente');

        $this->campusToDelete     = null;
        $this->campusToDeleteName = null;
        $this->isDeleteModalOpen  = false;
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
        $this->campusId         = null;
        $this->name             = '';
        $this->allCareers       = true;
        $this->selectedCareerIds = [];
        $this->is_active        = true;
        $this->resetValidation();
    }
}