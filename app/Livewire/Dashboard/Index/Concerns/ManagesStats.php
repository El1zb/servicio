<?php

namespace App\Livewire\Dashboard\Index\Concerns;

use App\Models\Period;
use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;

trait ManagesStats
{
    // ─── Propiedades de filtros ──────────────────────────────────────────────────

    public string $search       = '';
    public string $statusFilter = 'all';
    public string $sortBy       = 'recent';

    // ─── Setters de filtros (botones del dropdown custom del header) ───────────────

    public function setStatusFilter(string $value): void
    {
        $this->statusFilter = $value;
        $this->resetPage();
    }

    public function setSortBy(string $value): void
    {
        $this->sortBy = $value;
        $this->resetPage();
    }

    // ─── Query base filtrada (búsqueda + estado) ─────────────────────────────────
    // Compartida por el listado y las estadísticas, para que las tarjetas de
    // arriba siempre reflejen el mismo subconjunto de periodos que se ve abajo.

    protected function filteredPeriodsBaseQuery(): \Illuminate\Database\Eloquent\Builder
    {
        $query = Period::query();

        if ($this->search) {
            $query->where('name', 'like', '%' . $this->search . '%');
        }

        match ($this->statusFilter) {
            'active'   => $query->where('is_active', true),
            'inactive' => $query->where('is_active', false),
            default    => null,
        };

        return $query;
    }

    // ─── Query principal de periodos ─────────────────────────────────────────────

    protected function getPeriodsQuery(): LengthAwarePaginator
    {
        // Sin with('semesters')/with('students'): period-cards.blade.php solo
        // pinta los *_count de abajo, nunca las colecciones completas — antes
        // se traían todos los alumnos y semestres de los 12 periodos de la
        // página en cada carga/filtro, sin usarse.
        $query = $this->filteredPeriodsBaseQuery()
            ->withCount([
                'students',
                'files',
                'students as pending_students_count' => fn ($q) => $q->where('status', 'pendiente'),
                'documents as pending_review_documents_count' => fn ($q) => $q->where('documents.status', 'en_revision'),
            ]);

        // Ordenamiento
        match ($this->sortBy) {
            'oldest' => $query->orderBy('start_date', 'asc'),
            'name'   => $query->orderBy('name'),
            default  => $query->orderBy('start_date', 'desc'),
        };

        $periods = $query->paginate(12);

        // Decorar cada periodo con propiedades calculadas
        $periods->getCollection()->transform(function (Period $period) {
            $period->startFormatted = Carbon::parse($period->start_date)->format('d/m/Y');
            $period->endFormatted   = Carbon::parse($period->end_date)->format('d/m/Y');
            $period->hasStudents    = $period->students_count > 0;

            return $period;
        });

        return $periods;
    }
}