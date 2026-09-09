{{-- Badge Activo/Inactivo de las cards de catálogo (Campus/Carreras/Semestres).
     Se renderiza dos veces por card (mobile arriba-derecha, desktop dentro del
     header) alternando visibilidad con clases responsive — un solo dueño del
     markup en vez de repetirlo en cada *-cards.blade.php. --}}
@props(['active'])

<span class="period-card-status {{ $active ? 'is-active' : '' }}">
    <span class="period-card-status-dot"></span>
    {{ $active ? 'Activo' : 'Inactivo' }}
</span>
