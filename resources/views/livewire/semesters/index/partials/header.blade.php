{{-- Header --}}
<div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-6 rounded-xl shadow-sm p-6"
     style="background-color: var(--index-bg);">
    <div>
        <x-auth-header
            title="Semestres"
            description="Administración y gestión de semestres académicos."
            :center="false"
        />
    </div>
    <flux:button
        variant="primary"
        wire:click="create"
        icon="plus"
        class="group relative inline-flex items-center justify-center px-4 py-2
               rounded-[var(--radius-md)]
               bg-[var(--index-btn-primary-bg)]! text-[var(--index-btn-primary-text)]!
               shadow-lg shadow-[var(--index-btn-primary-shadow)]
               hover:bg-[var(--index-btn-primary-hover)]! hover:shadow-xl hover:-translate-y-0.5
               transition-all duration-300 disabled:opacity-60 disabled:cursor-not-allowed gap-2">
        Nuevo Semestre
    </flux:button>
</div>