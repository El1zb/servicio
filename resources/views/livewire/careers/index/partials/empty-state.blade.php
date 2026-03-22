{{-- Estado vacío --}}
<div class="text-center py-16 px-5 mx-4 my-2 rounded-2xl border border-dashed
            border-[var(--color-border-hover)] bg-[var(--color-card-bg)]">

    <div class="w-12 h-12 rounded-xl bg-[var(--color-icon-bg)] flex items-center justify-center mx-auto mb-4">
        <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"
             class="text-[var(--color-secondary)] opacity-60">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"
                  d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/>
        </svg>
    </div>

    <h3 class="text-sm font-medium text-[var(--color-primary-2)] mb-1.5">
        {{ $search ? 'Sin resultados' : 'No hay carreras registradas' }}
    </h3>
    <p class="text-xs font-light text-[var(--color-secondary)] mb-5 max-w-[260px] mx-auto leading-relaxed">
        {{ $search ? 'Intenta con otros términos de búsqueda.' : 'Crea la primera carrera para comenzar.' }}
    </p>

    @if(!$search)
        <flux:button variant="primary" wire:click="create" icon="plus">
            Crear Carrera
        </flux:button>
    @endif
</div>