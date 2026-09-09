{{-- Switcher de Configuración: mismo patrón que x-catalog-switcher, cada
     opción es su propia página/ruta; esto solo navega entre ellas. Solo
     tiene sentido para admins (Administradores/Papelera) — un estudiante
     únicamente tiene "General", así que no se le muestra ningún switcher. --}}
@if(auth()->user()->hasRole('admin'))
    <x-switcher :options="[
        ['label' => 'General',         'href' => route('settings.profile'),    'active' => request()->routeIs('settings.profile')],
        ['label' => 'Administradores', 'href' => route('admin.create-admin'),  'active' => request()->routeIs('admin.create-admin')],
        ['label' => 'Papelera',        'href' => route('admin.trash'),         'active' => request()->routeIs('admin.trash')],
    ]" {{ $attributes }} />
@endif
