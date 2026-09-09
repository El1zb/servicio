<?php

namespace App\Livewire\Admin\Careers\Concerns;

use App\Models\Campus;
use App\Models\Career;
use Illuminate\Validation\Rule;

trait ManagesCareers
{
    // ─── Propiedades ────────────────────────────────────────────────────────────

    public string $search           = '';
    public string $statusFilter     = 'all';
    public ?int   $careerId         = null;
    public string $name             = '';
    public bool   $allCampuses      = true;
    public array  $selectedCampusIds = [];
    public bool   $is_active        = true;
    public bool   $isOpen           = false;
    public bool   $isDeleteModalOpen = false;
    public ?int   $careerToDelete   = null;
    public ?string $careerToDeleteName = null;
    public int    $formInstance     = 0;

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
        $this->resetCareerInput();
        $this->formInstance++;
        $this->isOpen = true;
    }

    // ─── Abrir modal edición ─────────────────────────────────────────────────────

    public function edit(Career $career): void
    {
        $this->careerId   = $career->id;
        $this->name       = $career->name;
        $this->is_active  = (bool) $career->is_active;

        $linkedCampusIds        = $career->campuses()->pluck('campuses.id')->toArray();
        $this->allCampuses      = count($linkedCampusIds) === Campus::count();
        $this->selectedCampusIds = $this->allCampuses ? [] : $linkedCampusIds;

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
                    Rule::unique('careers', 'name')->ignore($this->careerId)->whereNull('deleted_at'),
                ],
            ],
            [
                'name.required' => 'Debes ingresar el nombre de la carrera.',
                'name.min'      => 'El nombre de la carrera debe tener al menos 3 caracteres.',
                'name.unique'   => 'La carrera ya existe, por favor elige otro nombre.',
            ]
        );

        $career = Career::updateOrCreate(
            ['id' => $this->careerId],
            ['name' => $this->name, 'is_active' => $this->is_active]
        );

        $career->campuses()->sync($this->allCampuses ? Campus::pluck('id') : $this->selectedCampusIds);

        $type   = $this->careerId ? 'info' : 'success';
        $action = $this->careerId ? 'actualizada' : 'creada';

        $this->dispatch('notify', type: $type, message: "Carrera {$action} correctamente");

        $this->closeModal();
    }

    // ─── Confirmar eliminación ───────────────────────────────────────────────────

    public function confirmDelete(int $id): void
    {
        $this->isOpen              = false;
        $this->careerToDelete      = $id;
        $this->careerToDeleteName  = Career::find($id)?->name;
        $this->isDeleteModalOpen   = true;
    }

    // ─── Eliminar carrera ────────────────────────────────────────────────────────

    public function deleteCareer(): void
    {
        if (! $this->careerToDelete) return;

        $career = Career::withCount('students')->find($this->careerToDelete);

        if (! $career) return;

        if ($career->students_count > 0) {
            $this->dispatch('notify', type: 'warning', message: 'No puedes eliminar una carrera con estudiantes registrados. Desactívala en su lugar.');
            $this->isDeleteModalOpen = false;
            return;
        }

        $career->delete();

        $this->dispatch('notify', type: 'error', message: 'Carrera eliminada correctamente');

        $this->careerToDelete     = null;
        $this->careerToDeleteName = null;
        $this->isDeleteModalOpen  = false;
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
        $this->careerId          = null;
        $this->name              = '';
        $this->allCampuses       = true;
        $this->selectedCampusIds = [];
        $this->is_active         = true;
        $this->resetValidation();
    }
}