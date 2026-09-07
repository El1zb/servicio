{{-- Switcher entre Revisión / Documentos Base / Gestión de Estudiantes:
     mismo patrón visual que x-catalog-switcher, en el topbar (con respaldo
     en mobile, donde el topbar no se muestra). --}}
@push('topbar-switcher')
    <div class="catalog-switcher">
        @foreach($tabs as $route => $data)
            <a href="{{ route($route, $period->id) }}" wire:navigate
               class="catalog-switcher-option {{ request()->routeIs($route) ? 'active' : '' }}">
                {{ $data['label'] }}
            </a>
        @endforeach
    </div>
@endpush

<div class="lg:hidden">
    <div class="catalog-switcher w-full">
        @foreach($tabs as $route => $data)
            <a href="{{ route($route, $period->id) }}" wire:navigate
               class="catalog-switcher-option {{ request()->routeIs($route) ? 'active' : '' }}">
                {{ $data['label'] }}
            </a>
        @endforeach
    </div>
</div>
