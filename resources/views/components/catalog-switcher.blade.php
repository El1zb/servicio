{{-- Switcher de Campus / Carreras / Semestres: los tres viven ahora bajo
     una sola entrada de sidebar ("Catálogos"), y este control decide cuál
     ruta/componente se muestra. Cada ruta sigue siendo su propia página
     Livewire independiente; esto solo navega entre ellas. --}}
<div class="catalog-switcher">
    <a href="{{ route('campuses.index') }}"
       wire:navigate
       class="catalog-switcher-option {{ request()->routeIs('campuses.index') ? 'active' : '' }}">
        Campus
    </a>
    <a href="{{ route('careers.index') }}"
       wire:navigate
       class="catalog-switcher-option {{ request()->routeIs('careers.index') ? 'active' : '' }}">
        Carreras
    </a>
    <a href="{{ route('semesters.index') }}"
       wire:navigate
       class="catalog-switcher-option {{ request()->routeIs('semesters.index') ? 'active' : '' }}">
        Semestres
    </a>
</div>
