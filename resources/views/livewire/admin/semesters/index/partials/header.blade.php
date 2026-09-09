{{-- El botón principal vive en el topbar (@push), con respaldo en mobile
     más abajo (mismo wire:click="create" en ambos casos). --}}
@push('topbar-switcher')
    <x-catalog-switcher />
@endpush

@push('header-filters')
    <x-status-filter-dropdown :value="$statusFilter" :labels="$statusFilterLabels" id="desktop" />
@endpush

@push('topbar-actions')
    <button type="button" onclick="topbarAction('create')" class="btn-primary">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14M5 12h14"/></svg>
        Nuevo Semestre
    </button>
@endpush

{{-- Header: solo mobile, el título ya está en la barra superior móvil
     (sidebar.blade.php) y en el topbar de desktop.
     Switcher fuera del contenedor blanco (pegado a la derecha, colores
     invertidos vía catalog-switcher--mobile, igual que en tabs-nav.blade.php);
     debajo, el contenedor con el selector de estado a la izquierda y el
     botón + a la derecha. --}}
<div class="lg:hidden flex justify-end">
    <x-catalog-switcher class="catalog-switcher--mobile" />
</div>

<div class="lg:hidden flex justify-between items-center gap-3">
    <x-status-filter-dropdown :value="$statusFilter" :labels="$statusFilterLabels" id="mobile" />
    <button type="button" wire:click="create" class="btn-primary !w-10 !h-10 !p-0 flex-shrink-0" aria-label="Nuevo Semestre">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14M5 12h14"/></svg>
    </button>
</div>