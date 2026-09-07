{{-- Header: el nombre del periodo ya se muestra en el título compartido del
     topbar (pasado como :title al layout); aquí solo va el link de regreso
     y el meta del periodo (fechas + estado). --}}
<div class="flex flex-wrap items-center justify-between gap-4">
    <a wire:navigate href="{{ route('periods') }}"
       class="inline-flex items-center gap-1.5 text-sm transition-colors duration-150"
       style="color: var(--color-secondary);"
       onmouseover="this.style.color='var(--color-primary-2)'"
       onmouseout="this.style.color='var(--color-secondary)'">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 19l-7-7 7-7"/></svg>
        Volver a periodos
    </a>

    <div class="flex items-center gap-3">
        <span class="text-sm" style="color: var(--color-secondary);">
            {{ \Carbon\Carbon::parse($period->start_date)->translatedFormat('d M') }}
            –
            {{ \Carbon\Carbon::parse($period->end_date)->translatedFormat('d M Y') }}
        </span>

        <span class="period-card-status {{ $period->is_active ? 'is-active' : '' }}">
            <span class="period-card-status-dot"></span>
            {{ $period->is_active ? 'Activo' : 'Inactivo' }}
        </span>
    </div>
</div>
