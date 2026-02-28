<?php

namespace App\Livewire\Dashboard\Index\Concerns;

use App\Models\Period;
use Illuminate\Validation\Rule;

trait ManagesPeriods
{
    // ─── Propiedades ────────────────────────────────────────────────────────────

    public $isOpen           = false;
    public $isDeleteModalOpen = false;

    public $periodId         = null;
    public $name             = '';
    public $start_date       = '';
    public $end_date         = '';
    public $selectedSemesters = [];
    public $is_active        = false;

    public $periodToDelete   = null;

    // ─── Watchers de paginación ──────────────────────────────────────────────────

    public function updatingSearch(): void        { $this->resetPage(); }
    public function updatingStatusFilter(): void  { $this->resetPage(); }
    public function updatingSortBy(): void        { $this->resetPage(); }

    // ─── Abrir modal creación ────────────────────────────────────────────────────

    public function createPeriod(): void
    {
        $this->resetPeriodFields(false);
        $this->is_active = true;
        $this->isOpen    = true;
    }

    // ─── Abrir modal edición ─────────────────────────────────────────────────────

    public function editPeriod(int $id): void
    {
        $period = Period::with('semesters')->findOrFail($id);

        $this->periodId         = $period->id;
        $this->name             = $period->name;
        $this->start_date       = $period->start_date;
        $this->end_date         = $period->end_date;
        $this->selectedSemesters = $period->semesters->pluck('id')->toArray();
        $this->is_active        = (bool) $period->is_active;

        $this->isOpen = true;
    }

    // ─── Guardar / actualizar ────────────────────────────────────────────────────

    public function save(): void
    {
        $this->validate(
            [
                'name' => [
                    'required', 'string', 'max:255',
                    Rule::unique('periods', 'name')->ignore($this->periodId),
                ],
                'start_date'        => 'required|date',
                'end_date'          => 'required|date|after_or_equal:start_date',
                'selectedSemesters' => 'required|array|min:1',
            ],
            [
                'name.required'             => 'El nombre del periodo es obligatorio',
                'name.unique'               => 'Ya existe un periodo con este nombre',
                'start_date.required'       => 'La fecha de inicio es obligatoria',
                'end_date.required'         => 'La fecha de fin es obligatoria',
                'end_date.after_or_equal'   => 'La fecha de fin debe ser posterior a la fecha de inicio',
                'selectedSemesters.required' => 'Debes seleccionar al menos un semestre',
                'selectedSemesters.min'      => 'Debes seleccionar al menos un semestre',
            ]
        );

        if ($this->periodId) {
            $period = Period::findOrFail($this->periodId);
            $period->update([
                'name'       => $this->name,
                'start_date' => $this->start_date,
                'end_date'   => $this->end_date,
                'is_active'  => $this->is_active,
            ]);
            [$toastType, $message] = ['info', 'Periodo actualizado exitosamente'];
        } else {
            $period = Period::create([
                'name'       => $this->name,
                'start_date' => $this->start_date,
                'end_date'   => $this->end_date,
                'is_active'  => $this->is_active,
            ]);
            [$toastType, $message] = ['success', 'Periodo creado exitosamente'];
        }

        $period->semesters()->sync($this->selectedSemesters ?? []);

        $this->isOpen = false;
        $this->resetPeriodFields(false);
        $this->dispatch('notify', type: $toastType, message: $message);
    }

    // ─── Activar / desactivar ────────────────────────────────────────────────────

    public function toggleActive(int $id): void
    {
        $period = Period::findOrFail($id);
        $period->update(['is_active' => ! $period->is_active]);
    }

    // ─── Confirmar eliminación ───────────────────────────────────────────────────

    public function confirmDelete(int $id): void
    {
        $this->periodToDelete   = $id;
        $this->isDeleteModalOpen = true;
    }

    // ─── Eliminar periodo ────────────────────────────────────────────────────────

    public function deletePeriod(): void
    {
        if (! $this->periodToDelete) return;

        $period = Period::with(['students.documents', 'files'])->find($this->periodToDelete);

        if (! $period) return;

        if ($period->is_active) {
            $this->dispatch('notify', type: 'error', message: 'No puedes eliminar un periodo activo');
            $this->periodToDelete    = null;
            $this->isDeleteModalOpen = false;
            return;
        }

        // Eliminar archivos físicos de documentos de estudiantes
        foreach ($period->students as $student) {
            foreach ($student->documents as $doc) {
                if ($doc->student_file_path && \Storage::disk('public')->exists($doc->student_file_path)) {
                    \Storage::disk('public')->delete($doc->student_file_path);
                }
            }
        }

        // Eliminar archivos físicos base del periodo
        foreach ($period->files as $file) {
            foreach (['file_path', 'example_path'] as $field) {
                if ($file->$field && \Storage::disk('public')->exists($file->$field)) {
                    \Storage::disk('public')->delete($file->$field);
                }
            }
        }

        $period->delete();

        $this->dispatch('notify', type: 'error', message: 'Periodo eliminado exitosamente');

        $this->periodToDelete    = null;
        $this->isDeleteModalOpen = false;
    }

    // ─── Reset de campos ─────────────────────────────────────────────────────────

    private function resetPeriodFields(bool $closeModal = true): void
    {
        $this->periodId          = null;
        $this->name              = '';
        $this->start_date        = '';
        $this->end_date          = '';
        $this->selectedSemesters = [];
        $this->is_active         = false;

        $this->resetValidation();

        if ($closeModal) {
            $this->isOpen = false;
        }
    }
}