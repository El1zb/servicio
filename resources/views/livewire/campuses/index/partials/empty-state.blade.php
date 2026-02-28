{{-- Estado vacío --}}
<div style="text-align: center; padding: 72px 20px; margin: 16px; border-radius: 16px; border: 1px dashed var(--index-border); background-color: var(--index-card-bg);">

    <div style="width: 48px; height: 48px; border-radius: 12px; background-color: var(--index-icon-bg); display: flex; align-items: center; justify-content: center; margin: 0 auto 14px;">
        <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"
             style="color: var(--index-icon-text); opacity: 0.6;">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"
                  d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
        </svg>
    </div>

    <h3 style="font-size: 14px; font-weight: 500; color: var(--index-text-primary); margin-bottom: 6px;">
        {{ $search ? 'Sin resultados' : 'No hay campus registrados' }}
    </h3>
    <p style="font-size: 12px; font-weight: 300; color: var(--index-text-secondary); margin-bottom: 20px; max-width: 260px; margin-left: auto; margin-right: auto; line-height: 1.5;">
        {{ $search ? 'Intenta con otros términos de búsqueda.' : 'Crea el primer campus para comenzar.' }}
    </p>

    @if(!$search)
        <flux:button variant="primary" wire:click="create" icon="plus">
            Crear Campus
        </flux:button>
    @endif
</div>