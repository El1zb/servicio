{{-- Estado vacío --}}
<div style="text-align: center; padding: 72px 20px; margin: 16px; border-radius: 16px; border: 1px dashed var(--index-border); background-color: var(--index-card-bg);">

    <div style="width: 48px; height: 48px; border-radius: 12px; background-color: var(--index-icon-bg); display: flex; align-items: center; justify-content: center; margin: 0 auto 14px;">
        <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"
             style="color: var(--index-icon-text); opacity: 0.6;">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"
                  d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/>
        </svg>
    </div>

    <h3 style="font-size: 14px; font-weight: 500; color: var(--index-text-primary); margin-bottom: 6px;">
        {{ $search ? 'Sin resultados' : 'No hay carreras registradas' }}
    </h3>
    <p style="font-size: 12px; font-weight: 300; color: var(--index-text-secondary); margin-bottom: 20px; max-width: 260px; margin-left: auto; margin-right: auto; line-height: 1.5;">
        {{ $search ? 'Intenta con otros términos de búsqueda.' : 'Crea la primera carrera para comenzar.' }}
    </p>

    @if(!$search)
        <flux:button variant="primary" wire:click="create" icon="plus">
            Crear Carrera
        </flux:button>
    @endif
</div>