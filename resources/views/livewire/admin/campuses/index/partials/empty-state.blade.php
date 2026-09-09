{{-- Empty State --}}
<div class="text-center py-20">
    <svg class="w-14 h-14 mx-auto mb-4" style="color: var(--color-secondary);" viewBox="0 0 32 32" fill="none">
        <path d="M21.5,14.75c0.41,0,0.75,0.34,0.75,0.75s-0.34,0.75-0.75,0.75s-0.75-0.34-0.75-0.75 S21.09,14.75,21.5,14.75z" fill="currentColor"/>
        <path d="M10.5,14.75c0.41,0,0.75,0.34,0.75,0.75s-0.34,0.75-0.75,0.75s-0.75-0.34-0.75-0.75 S10.09,14.75,10.5,14.75z" fill="currentColor"/>
        <polyline points="21.5,1.5 4.5,1.5 4.5,30.5 27.5,30.5 27.5,7.5" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-miterlimit="10"/>
        <polyline points="21.5,1.5 27.479,7.5 21.5,7.5 21.5,4" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-miterlimit="10"/>
        <path d="M14.5,18.5c0-0.83,0.67-1.5,1.5-1.5s1.5,0.67,1.5,1.5" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-miterlimit="10"/>
        <path d="M20.75,15.5c0,0.41,0.34,0.75,0.75,0.75s0.75-0.34,0.75-0.75s-0.34-0.75-0.75-0.75S20.75,15.09,20.75,15.5z" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-miterlimit="10"/>
        <path d="M11.25,15.5c0,0.41-0.34,0.75-0.75,0.75s-0.75-0.34-0.75-0.75s0.34-0.75,0.75-0.75S11.25,15.09,11.25,15.5z" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-miterlimit="10"/>
    </svg>

    <h3 class="text-lg font-bold mb-2" style="color: var(--color-primary-2);">
        {{ $search ? 'Sin resultados' : 'No hay campus registrados' }}
    </h3>
    <p class="mb-6 max-w-sm mx-auto text-sm" style="color: var(--color-secondary);">
        {{ $search ? 'No se encontraron campus con esos términos.' : 'Crea el primer campus para comenzar.' }}
    </p>
    @if(!$search)
        <button type="button" wire:click="create" class="btn-primary">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14M5 12h14"/></svg>
            Crear Campus
        </button>
    @endif
</div>
