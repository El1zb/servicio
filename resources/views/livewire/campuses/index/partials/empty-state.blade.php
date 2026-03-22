{{-- Estado vacío --}}
<div class="text-center py-16 px-5 mx-4 my-2 rounded-2xl border border-dashed
            border-[var(--color-border-hover)] bg-[var(--color-card-bg)]">

    <div class="w-12 h-12 rounded-xl bg-[var(--color-icon-bg)] flex items-center justify-center mx-auto mb-4">
        <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"
             class="text-[var(--color-secondary)] opacity-60">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"
                  d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
        </svg>
    </div>

    <h3 class="text-sm font-medium text-[var(--color-primary-2)] mb-1.5">
        {{ $search ? 'Sin resultados' : 'No hay campus registrados' }}
    </h3>
    <p class="text-xs font-light text-[var(--color-secondary)] mb-5 max-w-[260px] mx-auto leading-relaxed">
        {{ $search ? 'Intenta con otros términos de búsqueda.' : 'Crea el primer campus para comenzar.' }}
    </p>

    @if(!$search)
        <flux:button variant="primary" wire:click="create" icon="plus">
            Crear Campus
        </flux:button>
    @endif
</div>