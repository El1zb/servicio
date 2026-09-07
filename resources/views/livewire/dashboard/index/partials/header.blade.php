{{-- El botón principal vive en el topbar (@push), con respaldo en mobile
     más abajo (mismo wire:click="createPeriod" en ambos casos). --}}
@push('topbar-actions')
    <button type="button" onclick="topbarAction('createPeriod')" class="btn-primary">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14M5 12h14"/></svg>
        Nuevo Periodo
    </button>
@endpush

{{-- Header: solo mobile, en desktop el título ya está en el topbar --}}
<div class="lg:hidden flex flex-col lg:flex-row justify-between items-start lg:items-center gap-6 rounded-xl shadow-sm p-6"
     style="background-color: var(--color-card-bg);">
    <x-auth-header
        title="Periodos Académicos"
        description="Administración y seguimiento de periodos académicos."
        :center="false"
    />
    <button type="button" wire:click="createPeriod" class="lg:hidden btn-primary">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14M5 12h14"/></svg>
        Nuevo Periodo
    </button>
</div>