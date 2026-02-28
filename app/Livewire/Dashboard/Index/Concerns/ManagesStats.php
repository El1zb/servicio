<?php

namespace App\Livewire\Dashboard\Index\Concerns;

use App\Models\Period;
use App\Models\Student;
use Carbon\Carbon;

trait ManagesFilters
{
    // ── Filter state ───────────────────────────────────────────────
    public string $search       = '';
    public string $statusFilter = 'all';
    public string $sortBy       = 'recent';

    // Reset paginación al cambiar filtros
    public function updatingSearch(): void       { $this->resetPage(); }
    public function updatingStatusFilter(): void { $this->resetPage(); }
    public function updatingSortBy(): void       { $this->resetPage(); }

    // ── Query builder ──────────────────────────────────────────────
    protected function getPeriodsQuery()
    {
        $query = Period::with(['semesters', 'students'])
            ->withCount([
                'students',
                'files',
                'students as approved_students_count' => fn ($q) => $q->where('status', 'aprobado'),
                'students as pending_students_count'  => fn ($q) => $q->where('status', 'pendiente'),
                'students as rejected_students_count' => fn ($q) => $q->where('status', 'rechazado'),
            ]);

        if ($this->search) {
            $query->where('name', 'like', '%' . $this->search . '%');
        }

        match ($this->statusFilter) {
            'active'   => $query->where('is_active', true),
            'inactive' => $query->where('is_active', false),
            default    => null,
        };

        match ($this->sortBy) {
            'oldest' => $query->orderBy('start_date', 'asc'),
            'name'   => $query->orderBy('name'),
            default  => $query->orderBy('start_date', 'desc'),
        };

        // Decorar cada periodo con propiedades calculadas
        $result = $query->paginate(12);
        $result->getCollection()->transform(fn ($period) => $this->decoratePeriod($period));

        return $result;
    }

    // Agrega propiedades calculadas al modelo para la vista
    private function decoratePeriod($period)
    {
        $total = $period->students_count ?: 1;

        $period->approvalRate   = round(($period->approved_students_count / $total) * 100);
        $period->startFormatted = Carbon::parse($period->start_date)->format('d/m/Y');
        $period->endFormatted   = Carbon::parse($period->end_date)->format('d/m/Y');
        $period->hasStudents    = $period->students_count > 0;

        return $period;
    }

    // ── Stats ──────────────────────────────────────────────────────
    protected function getStats(): array
    {
        return [
            'total_periods'    => Period::count(),
            'active_periods'   => Period::where('is_active', true)->count(),
            'total_students'   => Student::count(),
            'pending_approvals'=> Student::where('status', 'pendiente')->count(),
        ];
    }
}