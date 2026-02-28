<?php

namespace App\Livewire\Dashboard\Index\Concerns;

use App\Models\Period;
use App\Models\Student;
use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;

trait ManagesStats
{
    // ─── Propiedades de filtros ──────────────────────────────────────────────────

    public string $search       = '';
    public string $statusFilter = 'all';
    public string $sortBy       = 'recent';

    // ─── Query principal de periodos ─────────────────────────────────────────────

    protected function getPeriodsQuery(): LengthAwarePaginator
    {
        $query = Period::with(['semesters', 'students'])
            ->withCount([
                'students',
                'files',
                'students as approved_students_count' => fn ($q) => $q->where('status', 'aprobado'),
                'students as pending_students_count'  => fn ($q) => $q->where('status', 'pendiente'),
                'students as rejected_students_count' => fn ($q) => $q->where('status', 'rechazado'),
            ]);

        // Búsqueda por nombre
        if ($this->search) {
            $query->where('name', 'like', '%' . $this->search . '%');
        }

        // Filtro por estado
        match ($this->statusFilter) {
            'active'   => $query->where('is_active', true),
            'inactive' => $query->where('is_active', false),
            default    => null,
        };

        // Ordenamiento
        match ($this->sortBy) {
            'oldest' => $query->orderBy('start_date', 'asc'),
            'name'   => $query->orderBy('name'),
            default  => $query->orderBy('start_date', 'desc'),
        };

        $periods = $query->paginate(12);

        // Decorar cada periodo con propiedades calculadas
        $periods->getCollection()->transform(function (Period $period) {
            $total = $period->students_count ?: 1;

            $period->approvalRate   = round(($period->approved_students_count / $total) * 100);
            $period->startFormatted = Carbon::parse($period->start_date)->format('d/m/Y');
            $period->endFormatted   = Carbon::parse($period->end_date)->format('d/m/Y');
            $period->hasStudents    = $period->students_count > 0;

            return $period;
        });

        return $periods;
    }

    // ─── Estadísticas globales ───────────────────────────────────────────────────

    protected function getStats(): array
    {
        return [
            'total_periods'    => Period::count(),
            'active_periods'   => Period::where('is_active', true)->count(),
            'total_students'   => Student::count(),
            'pending_approvals' => Student::where('status', 'pendiente')->count(),
        ];
    }
}