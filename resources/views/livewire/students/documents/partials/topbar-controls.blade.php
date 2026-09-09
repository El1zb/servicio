{{-- Buscador: en desktop vive en el topbar, en mobile en la barra superior
     móvil (ver sidebar.blade.php). Filtra ambas pestañas del switcher. --}}
@push('topbar-search')
    <div class="topbar-search-input-wrap">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16.6725 16.6412L21 21"/>
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11C19 15.4183 15.4183 19 11 19C6.58172 19 3 15.4183 3 11C3 6.58172 6.58172 3 11 3C15.4183 3 19 6.58172 19 11Z"/>
        </svg>
        <input type="text" placeholder="Buscar documento..." value="{{ $searchDocuments }}" oninput="topbarSearchInput(this.value, 'searchDocuments')" class="topbar-search-input"/>
    </div>
@endpush

{{-- Filtro de estado: junto al título de la página. Va empujado al layout
     (fuera del wire:id del componente), así que $wire no está disponible
     ahí — por eso, a diferencia del resto de mis selects, este habla con
     el componente vía window.Livewire.first() igual que los demás filtros
     de header-filters ya establecidos (revision-tab.blade.php, filters.blade.php). --}}
@push('header-filters')
    <div class="header-filter-dropdown"
         x-data="{ open: false, value: '{{ $statusFilter }}', label: '{{ $statusFilterLabels[$statusFilter] ?? 'Todos los estados' }}' }"
         @click.outside="open = false">
        <button type="button" class="header-filter-select" @click="open = !open">
            <span x-text="label"></span>
            <svg class="header-filter-select-chevron" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
            </svg>
        </button>

        <div class="header-filter-dropdown-panel" x-show="open" x-cloak x-transition>
            @foreach($statusFilterLabels as $value => $label)
                <button type="button" class="header-filter-dropdown-option" :class="{ 'is-selected': value === '{{ $value }}' }"
                        @click="value = '{{ $value }}'; label = '{{ $label }}'; open = false; window.Livewire.first().set('statusFilter', '{{ $value }}')">
                    {{ $label }}
                </button>
            @endforeach
        </div>
    </div>
@endpush
