{{-- Selector personalizado (píldora + panel Alpine) para filtrar por estado.
     Usado tanto en el topbar de escritorio (@push('header-filters')) como en
     el header mobile de Campus/Carreras/Semestres — un solo dueño del markup
     que antes estaba duplicado en cada header.blade.php.

     Al vivir renderizado dos veces a la vez (la copia de escritorio solo se
     oculta con display:none en mobile, sigue montada), Alpine no reinicia su
     x-data solo porque Livewire vuelva a renderizar el HTML con otro valor:
     la copia que no tocaste se queda con la opción vieja. wire:key atado al
     valor actual fuerza a Livewire a reemplazar el nodo (no parchearlo) cada
     vez que cambia, así ambas copias siempre arrancan con el valor real. --}}
@props(['value', 'labels', 'id' => 'filter'])

<div class="header-filter-dropdown"
     wire:key="status-filter-{{ $id }}-{{ $value }}"
     x-data="{ open: false, value: '{{ $value }}', label: '{{ $labels[$value] ?? 'Todos' }}' }"
     @click.outside="open = false">
    <button type="button" class="header-filter-select" @click="open = !open">
        <span x-text="label"></span>
        <svg class="header-filter-select-chevron" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
        </svg>
    </button>

    <div class="header-filter-dropdown-panel" x-show="open" x-cloak x-transition>
        @foreach($labels as $optionValue => $optionLabel)
            <button type="button"
                    class="header-filter-dropdown-option"
                    :class="{ 'is-selected': value === '{{ $optionValue }}' }"
                    @click="value = '{{ $optionValue }}'; label = '{{ $optionLabel }}'; open = false; window.Livewire.first().call('setStatusFilter', '{{ $optionValue }}')">
                {{ $optionLabel }}
            </button>
        @endforeach
    </div>
</div>
