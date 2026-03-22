{{-- Header --}}
<div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-6 rounded-xl shadow-sm p-6"
     style="background-color: var(--color-card-bg);">
    <x-auth-header
        title="Periodos Académicos"
        description="Administración y seguimiento de periodos académicos."
        :center="false"
    />
    <flux:button variant="primary" wire:click="createPeriod" icon="plus"
        class="group relative inline-flex items-center justify-center px-4 py-2 rounded-[var(--radius-md)]
               bg-[var(--color-primary)]! text-[var(--color-card-bg)]!
               shadow-lg shadow-[var(--color-border-hover)]
               hover:bg-[var(--color-primary-2)]! hover:shadow-xl hover:-translate-y-0.5
               transition-all duration-300 disabled:opacity-60 disabled:cursor-not-allowed gap-2">
        Nuevo Periodo
    </flux:button>
</div>