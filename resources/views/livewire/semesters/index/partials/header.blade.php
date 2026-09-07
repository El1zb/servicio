{{-- El botón principal vive en el topbar (@push), con respaldo en mobile
     más abajo (mismo wire:click="create" en ambos casos). --}}
@push('topbar-switcher')
    <x-catalog-switcher />
@endpush

@php
    $statusFilterLabels = ['all' => 'Todos', 'active' => 'Activos', 'inactive' => 'Inactivos'];
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
@endpush

@push('topbar-actions')
    <button type="button" onclick="topbarAction('create')" class="btn-primary">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14M5 12h14"/></svg>
        Nuevo Semestre
    </button>
@endpush

{{-- Header: solo mobile, en desktop el título ya está en el topbar --}}
<div class="lg:hidden flex flex-col lg:flex-row justify-between items-start lg:items-center gap-6 rounded-xl shadow-sm p-6"
     style="background-color: var(--color-card-bg);">
    <div>
        <x-auth-header
            title="Semestres"
            description="Administración y gestión de semestres académicos."
            :center="false"
        />
    </div>
    <x-catalog-switcher />
    <flux:select wire:model.live="statusFilter" class="w-full lg:w-48">
        @foreach($statusFilterLabels as $value => $label)
            <option value="{{ $value }}">{{ $label }}</option>
        @endforeach
    </flux:select>
    <button type="button" wire:click="create" class="lg:hidden btn-primary">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14M5 12h14"/></svg>
        Nuevo Semestre
    </button>
</div>