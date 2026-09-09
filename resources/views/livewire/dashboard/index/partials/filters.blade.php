{{-- Buscador: en desktop vive en el topbar (@push), en mobile se queda en
     el grid de filtros de acá abajo. Mismo wire:model en ambos casos. --}}
@push('topbar-search')
    <div class="topbar-search-input-wrap">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M16.6725 16.6412L21 21"/>
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M19 11C19 15.4183 15.4183 19 11 19C6.58172 19 3 15.4183 3 11C3 6.58172 6.58172 3 11 3C15.4183 3 19 6.58172 19 11Z"/>
        </svg>
        <input type="text" placeholder="Buscar por nombre..." value="{{ $search }}" oninput="topbarSearchInput(this.value)" class="topbar-search-input"/>
    </div>
@endpush

{{-- Filtros de estado/orden: en desktop viven en el header (junto al título
     y el botón "Nuevo Periodo"); en mobile son los mismos selects
     personalizados, ver header.blade.php. $statusFilterLabels/$sortByLabels
     vienen de index.blade.php. --}}
@push('header-filters')
    <div class="header-filter-dropdown" wire:ignore
         x-data="{ open: false, value: '{{ $statusFilter }}', label: '{{ $statusFilterLabels[$statusFilter] ?? 'Todos' }}' }"
         @click.outside="open = false">
        <button type="button" class="header-filter-select" @click="open = !open">
            <span x-text="label"></span>
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

    <div class="header-filter-dropdown" wire:ignore
         x-data="{ open: false, value: '{{ $sortBy }}', label: '{{ $sortByLabels[$sortBy] ?? 'Más recientes' }}' }"
         @click.outside="open = false">
        <button type="button" class="header-filter-select" @click="open = !open">
            <span x-text="label"></span>
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
@endpush