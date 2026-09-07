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
     y el botón "Nuevo Periodo"), en mobile se quedan acá abajo. Mismos
     wire:model en ambos casos. --}}
@php
    $statusFilterLabels = ['all' => 'Todos', 'active' => 'Activos', 'inactive' => 'Inactivos'];
    $sortByLabels = ['recent' => 'Más recientes', 'oldest' => 'Más antiguos', 'name' => 'Nombre (A–Z)'];
@endphp

@push('header-filters')
    <div class="header-filter-dropdown"
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

    <div class="header-filter-dropdown"
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

{{-- Filters (mobile/tablet) --}}
<div class="lg:hidden rounded-xl p-5">
    <div class="grid grid-cols-1 md:grid-cols-12 gap-3 items-center">

        {{-- Búsqueda (solo mobile/tablet, en desktop está en el topbar) --}}
        <div class="md:col-span-6 relative">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                     style="color: var(--color-primary-2);">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M16.6725 16.6412L21 21"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M19 11C19 15.4183 15.4183 19 11 19C6.58172 19 3 15.4183 3 11C3 6.58172 6.58172 3 11 3C15.4183 3 19 6.58172 19 11Z"/>
                </svg>
            </div>
            <input
                type="text"
                placeholder="Buscar por nombre..."
                wire:model.live="search"
                class="w-full pl-10 pr-4 py-2.5 rounded-lg text-sm transition-all"
                style="background-color: var(--color-card-bg); color: var(--color-primary-2); border: 1px solid var(--color-border-hover);"
            />
        </div>

        {{-- Filtro estado --}}
        <div class="md:col-span-3 lg:col-span-2">
            <flux:select
                wire:model.live="statusFilter"
                class="w-full px-4 py-2.5 rounded-lg text-sm font-medium transition-all"
                style="background-color: var(--color-card-bg); color: var(--color-primary-2); border: 1px solid var(--color-border-hover);">
                <option value="all">Todos los estados</option>
                <option value="active">Solo activos</option>
                <option value="inactive">Solo inactivos</option>
            </flux:select>
        </div>

        {{-- Ordenamiento --}}
        <div class="md:col-span-3 lg:col-span-3">
            <flux:select
                wire:model.live="sortBy"
                class="w-full px-4 py-2.5 rounded-lg text-sm font-medium transition-all"
                style="background-color: var(--color-card-bg); color: var(--color-primary-2); border: 1px solid var(--color-border-hover);">
                <option value="recent">Más recientes</option>
                <option value="oldest">Más antiguos</option>
                <option value="name">Nombre (A–Z)</option>
            </flux:select>
        </div>

    </div>
</div>