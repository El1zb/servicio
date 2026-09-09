{{-- Switcher de Campus / Carreras / Semestres: los tres viven ahora bajo
     una sola entrada de sidebar ("Catálogos"), y este control decide cuál
     ruta/componente se muestra. Cada ruta sigue siendo su propia página
     Livewire independiente; esto solo navega entre ellas. --}}
{{-- {{ $attributes }} permite pasar clases extra (p.ej. catalog-switcher--mobile)
     desde donde se use <x-catalog-switcher>, igual que en tabs-nav.blade.php. --}}
<x-switcher :options="[
    ['label' => 'Campus',    'href' => route('campuses.index'),  'active' => request()->routeIs('campuses.index')],
    ['label' => 'Carreras',  'href' => route('careers.index'),   'active' => request()->routeIs('careers.index')],
    ['label' => 'Semestres', 'href' => route('semesters.index'), 'active' => request()->routeIs('semesters.index')],
]" {{ $attributes }} />
