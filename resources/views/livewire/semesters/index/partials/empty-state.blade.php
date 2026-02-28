{{-- Estado vacío --}}
<div style="text-align: center; padding: 72px 20px; margin: 16px; border-radius: 16px; border: 1px dashed var(--index-border); background-color: var(--index-card-bg);">

    <div style="width: 48px; height: 48px; border-radius: 12px; background-color: var(--index-icon-bg); display: flex; align-items: center; justify-content: center; margin: 0 auto 14px;">
        <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"
             style="color: var(--index-icon-text); opacity: 0.6;">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"
                  d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
        </svg>
    </div>

    <h3 style="font-size: 14px; font-weight: 500; color: var(--index-text-primary); margin-bottom: 6px;">
        {{ $search ? 'Sin resultados' : 'No hay semestres registrados' }}
    </h3>
    <p style="font-size: 12px; font-weight: 300; color: var(--index-text-secondary); margin-bottom: 20px; max-width: 260px; margin-left: auto; margin-right: auto; line-height: 1.5;">
        {{ $search ? 'Intenta con otros términos de búsqueda.' : 'Crea el primer semestre para comenzar.' }}
    </p>

    @if(!$search)
        <flux:button variant="primary" wire:click="create" icon="plus">
            Crear Semestre
        </flux:button>
    @endif
</div>