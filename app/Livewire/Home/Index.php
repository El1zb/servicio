<?php

namespace App\Livewire\Home;

use App\Models\Campus;
use App\Models\Career;
use App\Models\File;
use App\Models\Period;
use App\Models\Student;
use Illuminate\Support\Carbon;
use Livewire\Component;

class Index extends Component
{
    public function render()
    {
        $totalPeriods     = Period::count();
        $activePeriods    = Period::where('is_active', true)->count();
        $totalStudents    = Student::count();
        $pendingApprovals = Student::where('status', 'pendiente')->count();
        $totalFiles       = File::count();

        $stats = [
            'total_periods'     => $totalPeriods,
            'active_periods'    => $activePeriods,
            'total_students'    => $totalStudents,
            'pending_approvals' => $pendingApprovals,
            'pending_percent'   => $totalStudents > 0 ? (int) round($pendingApprovals / $totalStudents * 100) : 0,
            'total_files'       => $totalFiles,
            'files_per_period'  => $totalPeriods > 0 ? round($totalFiles / $totalPeriods, 1) : 0,
        ];

        $studentsByCampus = Campus::withCount('students')
            ->orderByDesc('students_count')
            ->get()
            ->map(fn (Campus $campus) => [
                'label' => $campus->name,
                'value' => $campus->students_count,
            ])
            ->values();

        $upcomingFiles = File::query()
            ->whereNotNull('limit_date')
            ->where('limit_date', '>=', now()->startOfDay())
            ->orderBy('limit_date')
            ->with('period:id,name')
            ->take(4)
            ->get()
            ->map(fn (File $file) => [
                'day'    => Carbon::parse($file->limit_date)->format('d'),
                'month'  => strtoupper(Carbon::parse($file->limit_date)->format('M')),
                'name'   => $file->name,
                'period' => $file->period->name ?? '—',
            ]);

        // Escala de grises (oscuro a claro) en vez de colores de estado,
        // para que la dona combine con la paleta blanco/negro de la app.
        $statusCounts = [
            'aprobado'  => Student::where('status', 'aprobado')->count(),
            'pendiente' => Student::where('status', 'pendiente')->count(),
            'rechazado' => Student::where('status', 'rechazado')->count(),
        ];
        $statusTotal = array_sum($statusCounts) ?: 1;

        $studentsByStatus = [
            ['label' => 'Aprobados', 'value' => $statusCounts['aprobado'], 'color' => 'var(--color-primary-2)', 'percent' => round($statusCounts['aprobado'] / $statusTotal * 100)],
            ['label' => 'Pendientes', 'value' => $statusCounts['pendiente'], 'color' => 'var(--color-secondary)', 'percent' => round($statusCounts['pendiente'] / $statusTotal * 100)],
            ['label' => 'Rechazados', 'value' => $statusCounts['rechazado'], 'color' => 'var(--color-border-hover)', 'percent' => round($statusCounts['rechazado'] / $statusTotal * 100)],
        ];

        $studentsByCareer = Career::withCount('students')
            ->orderByDesc('students_count')
            ->get()
            ->filter(fn (Career $career) => $career->students_count > 0)
            ->take(6)
            ->map(fn (Career $career) => [
                'label' => $career->name,
                'value' => $career->students_count,
            ])
            ->values();

        return view('livewire.home.index', compact(
            'stats', 'studentsByCampus', 'upcomingFiles', 'studentsByStatus', 'studentsByCareer'
        ));
    }
}
