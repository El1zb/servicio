{{-- Estadísticas del periodo: mismo patrón que las de Inicio, pero
     enfocadas en lo que de verdad importa revisar aquí (no cifras
     genéricas repetidas). --}}
@php
    $totalStudents    = $stats['total'];
    $pendingStudents  = $stats['pending'];
    $approvedStudents = $stats['approved'];
    $pendingDocuments = $stats['pendingDocuments'];

    $statCards = [
        [
            'label'       => 'Pendientes de aceptar',
            'value'       => $pendingStudents,
            'description' => $totalStudents > 0
                ? round($pendingStudents / $totalStudents * 100) . '% del total'
                : 'Aún no hay estudiantes',
            'dark'        => true,
            'icon'        => '<path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>',
        ],
        [
            'label'       => 'Documentos por revisar',
            'value'       => $pendingDocuments,
            'description' => 'entregados por estudiantes',
            'dark'        => false,
            'icon'        => '<path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>',
        ],
        [
            'label'       => 'Aprobados',
            'value'       => $approvedStudents,
            'description' => 'listos para el periodo',
            'dark'        => false,
            'icon'        => '<path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>',
        ],
        [
            'label'       => 'Total Estudiantes',
            'value'       => $totalStudents,
            'description' => 'registrados en el periodo',
            'dark'        => false,
            'icon'        => '<path d="M5 21C5 17.134 8.13401 14 12 14C15.866 14 19 17.134 19 21M16 7C16 9.20914 14.2091 11 12 11C9.79086 11 8 9.20914 8 7C8 4.79086 9.79086 3 12 3C14.2091 3 16 4.79086 16 7Z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>',
        ],
    ];
@endphp

<div class="flex overflow-x-auto snap-x scrollbar-none gap-4 pb-2 md:grid md:grid-cols-2 md:gap-5 md:overflow-visible md:pb-0 lg:grid-cols-4">
    @foreach($statCards as $card)
        <div class="stat-card {{ $card['dark'] ? 'stat-card-dark' : '' }} shrink-0 w-[75%] max-w-xs snap-start md:w-auto md:max-w-none md:shrink md:snap-align-none">
            <div class="stat-card-top">
                <p class="stat-card-label">{{ $card['label'] }}</p>
                <div class="stat-card-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">{!! $card['icon'] !!}</svg>
                </div>
            </div>

            <p class="stat-card-value">{{ $card['value'] }}</p>

            <p class="stat-card-description">
                <span class="stat-card-dot"></span>
                {{ $card['description'] }}
            </p>
        </div>
    @endforeach
</div>
