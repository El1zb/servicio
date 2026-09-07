{{-- Switcher de Configuración: mismo patrón que x-catalog-switcher, cada
     opción es su propia página/ruta; esto solo navega entre ellas. Solo
     tiene sentido para admins (Administradores/Papelera) — un estudiante
     únicamente tiene "General", así que no se le muestra ningún switcher. --}}
@if(auth()->user()->hasRole('admin'))
    <div class="catalog-switcher">
        <a href="{{ route('settings.profile') }}"
           wire:navigate
           class="catalog-switcher-option {{ request()->routeIs('settings.profile') ? 'active' : '' }}">
            General
        </a>
        <a href="{{ route('admin.create-admin') }}"
           wire:navigate
           class="catalog-switcher-option {{ request()->routeIs('admin.create-admin') ? 'active' : '' }}">
            Administradores
        </a>
        <a href="{{ route('admin.trash') }}"
           wire:navigate
           class="catalog-switcher-option {{ request()->routeIs('admin.trash') ? 'active' : '' }}">
            Papelera
        </a>
    </div>
@endif
