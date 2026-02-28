<?php

namespace App\Livewire\Dashboard\Index\Concerns;

use App\Models\Period;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

trait ManagesPeriods
{
    // ── Abrir modal creación ───────────────────────────────────────
    public function createPeriod(): void
    {
        $this->resetFormFields();
        $this->is_active = true;
        $this->isOpen    = true;
    }

    // ── Abrir modal edición ───────────────────────────────────────
    public function editPeriod(int $id): void
    {
        $period = Period::with('semesters')->findOrFail($id);

        $this->periodId          = $period->id;
        $this->name              = $period->name;
        $this->start_date        = $period->start_date;
        $this->end_date          = $period->end_date;
        $this->selectedSemesters = $period->semesters->pluck('id')->toArray();
        $this->is_active         = (bool) $period->is_active;

        $this->isOpen = true;
    }

    // ── Guardar (crear o actualizar) ──────────────────────────────
    public function save(): void
    {
        $this->validate($this->periodRules(), $this->periodMessages());

        if ($this->periodId) {
            $period = Period::findOrFail($this->periodId);
            $period->update($this->periodData());
            $toastType = 'info';
            $message   = 'Periodo actualizado exitosamente';
        } else {
            $period    = Period::create($this->periodData());
            $toastType = 'success';
            $message   = 'Periodo creado exitosamente';
        }

        $period->semesters()->sync($this->selectedSemesters ?? []);

        $this->isOpen = false;
        $this->resetFormFields();
        $this->dispatch('notify', type: $toastType, message: $message);
    }

    // ── Confirmar eliminación ─────────────────────────────────────
    public function confirmDelete(int $id): void
    {
        $this->periodToDelete    = $id;
        $this->isDeleteModalOpen = true;
    }

    // ── Eliminar periodo ──────────────────────────────────────────
    public function deletePeriod(): void
    {
        if (! $this->periodToDelete) return;

        $period = Period::with(['students.documents', 'files'])->find($this->periodToDelete);
        if (! $period) return;

        if ($period->is_active) {
            $this->dispatch('notify', type: 'error', message: 'No puedes eliminar un periodo activo');
            $this->resetDeleteState();
            return;
        }

        $this->purgeStudentDocumentFiles($period);
        $this->purgePeriodBaseFiles($period);

        $period->delete();

        $this->dispatch('notify', type: 'error', message: 'Periodo eliminado exitosamente');
        $this->resetDeleteState();
    }

    // ── Helpers privados ──────────────────────────────────────────

    private function periodData(): array
    {
        return [
            'name'       => $this->name,
            'start_date' => $this->start_date,
            'end_date'   => $this->end_date,
            'is_active'  => $this->is_active,
        ];
    }

    private function periodRules(): array
    {
        return [
            'name'              => ['required', 'string', 'max:255', Rule::unique('periods', 'name')->ignore($this->periodId)],
            'start_date'        => 'required|date',
            'end_date'          => 'required|date|after_or_equal:start_date',
            'selectedSemesters' => 'required|array|min:1',
        ];
    }

    private function periodMessages(): array
    {
        return [
            'name.required'              => 'El nombre del periodo es obligatorio',
            'name.unique'                => 'Ya existe un periodo con este nombre',
            'start_date.required'        => 'La fecha de inicio es obligatoria',
            'end_date.required'          => 'La fecha de fin es obligatoria',
            'end_date.after_or_equal'    => 'La fecha de fin debe ser posterior a la fecha de inicio',
            'selectedSemesters.required' => 'Debes seleccionar al menos un semestre',
            'selectedSemesters.min'      => 'Debes seleccionar al menos un semestre',
        ];
    }

    private function purgeStudentDocumentFiles(Period $period): void
    {
        foreach ($period->students as $student) {
            foreach ($student->documents as $doc) {
                if ($doc->student_file_path && Storage::disk('public')->exists($doc->student_file_path)) {
                    Storage::disk('public')->delete($doc->student_file_path);
                }
            }
        }
    }

    private function purgePeriodBaseFiles(Period $period): void
    {
        foreach ($period->files as $file) {
            foreach (['file_path', 'example_path'] as $attr) {
                if ($file->$attr && Storage::disk('public')->exists($file->$attr)) {
                    Storage::disk('public')->delete($file->$attr);
                }
            }
        }
    }

    private function resetFormFields(): void
    {
        $this->periodId          = null;
        $this->name              = '';
        $this->start_date        = '';
        $this->end_date          = '';
        $this->selectedSemesters = [];
        $this->is_active         = false;
        $this->resetValidation();
    }

    private function resetDeleteState(): void
    {
        $this->periodToDelete    = null;
        $this->isDeleteModalOpen = false;
    }
}