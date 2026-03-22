{{-- Empty State --}}
<div class="text-center py-20 rounded-2xl border border-dashed"
     style="background-color: var(--color-card-bg); border-color: var(--color-border-hover);">
    <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl mb-4"
         style="background-color: var(--color-icon-bg);">
        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"
             style="color: var(--color-secondary);">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
        </svg>
    </div>
    <h3 class="text-lg font-bold mb-2" style="color: var(--color-primary-2);">
        {{ $search ? 'Sin resultados' : 'No hay periodos académicos' }}
    </h3>
    <p class="mb-6 max-w-sm mx-auto text-sm" style="color: var(--color-secondary);">
        {{ $search ? 'No se encontraron periodos con esos términos.' : 'Crea el primer periodo para comenzar.' }}
    </p>
    @if(!$search)
        <flux:button variant="primary" wire:click="createPeriod" icon="plus">
            Crear Periodo
        </flux:button>
    @endif
</div>