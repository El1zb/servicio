{{-- Stats --}}
<div class="flex overflow-x-auto snap-x scrollbar-none gap-4 pb-2 md:grid md:grid-cols-2 md:gap-5 md:overflow-visible md:pb-0 lg:grid-cols-4">

    @php
        $periodWord  = fn (int $n) => $n === 1 ? 'periodo' : 'periodos';
        $studentWord = fn (int $n) => $n === 1 ? 'estudiante' : 'estudiantes';

        $statCards = [
            [
                'label'       => 'Aprobaciones Pendientes',
                'value'       => $stats['pending_approvals'],
                'description' => "{$stats['pending_percent']}% de los " . $studentWord($stats['total_students']),
                'dark'        => true,
                'icon'        => '<path d="M23 12C23 18.0751 18.0751 23 12 23C5.92487 23 1 18.0751 1 12C1 5.92487 5.92487 1 12 1C18.0751 1 23 5.92487 23 12ZM3.00683 12C3.00683 16.9668 7.03321 20.9932 12 20.9932C16.9668 20.9932 20.9932 16.9668 20.9932 12C20.9932 7.03321 16.9668 3.00683 12 3.00683C7.03321 3.00683 3.00683 7.03321 3.00683 12Z"/><path d="M12 5C11.4477 5 11 5.44771 11 6V12.4667C11 12.4667 11 12.7274 11.1267 12.9235C11.2115 13.0898 11.3437 13.2343 11.5174 13.3346L16.1372 16.0019C16.6155 16.278 17.2271 16.1141 17.5032 15.6358C17.7793 15.1575 17.6155 14.5459 17.1372 14.2698L13 11.8812V6C13 5.44772 12.5523 5 12 5Z"/>',
                'icon_fill'   => true,
            ],
            [
                'label'       => 'Periodos Activos',
                'value'       => $stats['active_periods'],
                'description' => "de {$stats['total_periods']} " . $periodWord($stats['total_periods']) . ' en total',
                'dark'        => false,
                'icon'        => '<path d="M4 12.6111L8.92308 17.5L20 6.5" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>',
                'icon_fill'   => false,
            ],
            [
                'label'       => 'Documentos Configurados',
                'value'       => $stats['total_files'],
                'description' => "≈{$stats['files_per_period']} por " . $periodWord(1),
                'dark'        => false,
                'icon'        => '<path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>',
                'icon_fill'   => false,
            ],
            [
                'label'       => 'Total Estudiantes',
                'value'       => $stats['total_students'],
                'description' => "en {$stats['total_periods']} " . $periodWord($stats['total_periods']),
                'dark'        => false,
                'icon'        => '<path d="M5 21C5 17.134 8.13401 14 12 14C15.866 14 19 17.134 19 21M16 7C16 9.20914 14.2091 11 12 11C9.79086 11 8 9.20914 8 7C8 4.79086 9.79086 3 12 3C14.2091 3 16 4.79086 16 7Z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>',
                'icon_fill'   => false,
            ],
        ];
    @endphp

    @foreach($statCards as $card)
        <div class="stat-card {{ $card['dark'] ? 'stat-card-dark' : '' }} shrink-0 w-[75%] max-w-xs snap-start md:w-auto md:max-w-none md:shrink md:snap-align-none">
            <div class="stat-card-top">
                <p class="stat-card-label">{{ $card['label'] }}</p>
                <div class="stat-card-icon">
                    <svg viewBox="0 0 24 24" fill="{{ $card['icon_fill'] ? 'currentColor' : 'none' }}" stroke="currentColor">
                        {!! $card['icon'] !!}
                    </svg>
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
