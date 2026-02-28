@php
    $statCards = [
        [
            'label'      => 'Total Periodos',
            'value'      => $stats['total_periods'],
            'bg'         => 'var(--index-status-normal-bg)',
            'icon_color' => 'var(--index-status-normal-icon)',
            'icon'       => 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z',
        ],
        [
            'label'      => 'Periodos Activos',
            'value'      => $stats['active_periods'],
            'bg'         => 'var(--index-status-approved-bg)',
            'icon_color' => 'var(--index-status-approved-icon)',
            'icon'       => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z',
        ],
        [
            'label'      => 'Total Estudiantes',
            'value'      => $stats['total_students'],
            'bg'         => 'var(--index-icon-bg)',
            'icon_color' => 'var(--index-icon-text)',
            'icon'       => 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z',
        ],
        [
            'label'      => 'Aprobaciones Pendientes',
            'value'      => $stats['pending_approvals'],
            'bg'         => 'rgba(232,210,50,0.1)',
            'icon_color' => 'var(--index-status-pending)',
            'icon'       => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z',
        ],
    ];
@endphp

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5">
    @foreach($statCards as $card)
        <div class="rounded-xl p-6 shadow-lg" style="background-color: var(--index-bg);">
            <div class="flex items-center gap-3">

                <div class="w-12 h-12 rounded-lg flex items-center justify-center"
                     style="background-color: {{ $card['bg'] }};">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                         style="color: {{ $card['icon_color'] }};">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $card['icon'] }}"/>
                    </svg>
                </div>

                <div>
                    <p class="text-xs font-medium uppercase tracking-wide" style="color: var(--index-text-secondary);">
                        {{ $card['label'] }}
                    </p>
                    <p class="text-2xl font-bold mt-0.5" style="color: var(--index-text-primary);">
                        {{ $card['value'] }}
                    </p>
                </div>

            </div>
        </div>
    @endforeach
</div>