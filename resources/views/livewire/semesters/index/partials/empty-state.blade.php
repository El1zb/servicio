{{-- Estado vacío --}}
<div class="text-center py-16 px-5 mx-4 my-2 rounded-2xl border border-dashed
            border-[var(--color-border-hover)] bg-[var(--color-card-bg)]">

    <div class="w-12 h-12 rounded-xl bg-[var(--color-icon-bg)] flex items-center justify-center mx-auto mb-4">
        <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"
             class="text-[var(--color-secondary)] opacity-60">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"
                  d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
        </svg>
    </div>

    <h3 class="text-sm font-medium text-[var(--color-primary-2)] mb-1.5">
        {{ $search ? 'Sin resultados' : 'No hay semestres registrados' }}
    </h3>
    <p class="text-xs font-light text-[var(--color-secondary)] mb-5 max-w-[260px] mx-auto leading-relaxed">
        {{ $search ? 'Intenta con otros términos de búsqueda.' : 'Crea el primer semestre para comenzar.' }}
    </p>

    @if(!$search)
        <flux:button variant="primary" wire:click="create" icon="plus">
            Crear Semestre
        </flux:button>
    @endif
</div>