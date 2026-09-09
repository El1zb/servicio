{{-- Switcher genérico (píldora segmentada). Único dueño del markup que antes
     estaba duplicado en x-catalog-switcher, x-settings-switcher y tabs-nav.

     Cada opción es un array:
       label  → texto visible
       active → si es la opción seleccionada
       count  → (opcional) número que indica cuántos elementos hay detrás
       href   → navega a esa ruta (wire:navigate)
       click  → acción Livewire, cuando el switcher cambia estado en la misma página --}}
@props(['options'])

<div {{ $attributes->merge(['class' => 'catalog-switcher']) }}>
    @foreach($options as $option)
        @php $tag = isset($option['href']) ? 'a' : 'button'; @endphp

        <{{ $tag }}
            @isset($option['href']) href="{{ $option['href'] }}" wire:navigate
            @else type="button" wire:click="{{ $option['click'] }}" @endisset
            class="catalog-switcher-option {{ ($option['active'] ?? false) ? 'active' : '' }}">
            {{ $option['label'] }}
            @isset($option['count'])
                <span class="catalog-switcher-count">{{ $option['count'] }}</span>
            @endisset
        </{{ $tag }}>
    @endforeach
</div>
