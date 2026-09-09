{{-- El botón principal vive en el topbar (@push), con respaldo en mobile
     más abajo (mismo wire:click="createPeriod" en ambos casos). --}}
@push('topbar-actions')
    <button type="button" onclick="topbarAction('createPeriod')" class="btn-primary">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14M5 12h14"/></svg>
        Nuevo Periodo
    </button>
@endpush

{{-- Header: solo mobile, el título ya está en la barra superior móvil
     (sidebar.blade.php). Mismos selects personalizados que desktop
     (header-filter-dropdown, ver filters.blade.php) + botón crear en icono,
     sin card de fondo: sigue el gris de la página. --}}
<div class="lg:hidden flex items-center gap-2">

    <div class="header-filter-dropdown flex-1 min-w-0" wire:ignore
         x-data="{ open: false, value: '{{ $statusFilter }}', label: '{{ $statusFilterLabels[$statusFilter] ?? 'Todos' }}' }"
         @click.outside="open = false">
        <button type="button" class="header-filter-select w-full" @click="open = !open">
            <span x-text="label" class="truncate"></span>
            <svg class="header-filter-select-chevron" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
            </svg>
        </button>

        <div class="header-filter-dropdown-panel" x-show="open" x-cloak x-transition>
            @foreach($statusFilterLabels as $value => $label)
                <button type="button"
                        class="header-filter-dropdown-option"
                        :class="{ 'is-selected': value === '{{ $value }}' }"
                        @click="value = '{{ $value }}'; label = '{{ $label }}'; open = false; window.Livewire.first().call('setStatusFilter', '{{ $value }}')">
                    {{ $label }}
                </button>
            @endforeach
        </div>
    </div>

    <div class="header-filter-dropdown flex-1 min-w-0" wire:ignore
         x-data="{ open: false, value: '{{ $sortBy }}', label: '{{ $sortByLabels[$sortBy] ?? 'Más recientes' }}' }"
         @click.outside="open = false">
        <button type="button" class="header-filter-select w-full" @click="open = !open">
            <span x-text="label" class="truncate"></span>
            <svg class="header-filter-select-chevron" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
            </svg>
        </button>

        <div class="header-filter-dropdown-panel" x-show="open" x-cloak x-transition>
            @foreach($sortByLabels as $value => $label)
                <button type="button"
                        class="header-filter-dropdown-option"
                        :class="{ 'is-selected': value === '{{ $value }}' }"
                        @click="value = '{{ $value }}'; label = '{{ $label }}'; open = false; window.Livewire.first().call('setSortBy', '{{ $value }}')">
                    {{ $label }}
                </button>
            @endforeach
        </div>
    </div>

    <button type="button" wire:click="createPeriod" title="Nuevo Periodo"
            class="btn-primary !w-10 !px-0 flex-shrink-0">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14M5 12h14"/></svg>
    </button>
</div>