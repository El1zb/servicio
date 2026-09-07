{{-- Select personalizado: mismo estilo que mis combobox de filtros (blanco,
     borde gris, redondeado, panel flotante) en vez del select nativo. --}}
@props(['wireModel', 'value' => null, 'options' => [], 'placeholder' => 'Seleccionar'])

<div {{ $attributes->merge(['class' => 'relative']) }}
    wire:ignore
    x-data="{
        value: @js($value !== null ? (string) $value : null),
        open: false,
        options: @js(collect($options)->map(fn ($label, $val) => ['value' => (string) $val, 'label' => $label])->values()),
        label() {
            const found = this.options.find(o => o.value === this.value);
            return found ? found.label : @js($placeholder);
        },
        select(opt) {
            this.value = opt.value;
            $wire.set('{{ $wireModel }}', opt.value);
            this.open = false;
        }
    }"
    @click.outside="open = false">
    <button type="button" class="header-filter-select review-field w-full" @click="open = !open">
        <span class="truncate flex-1 min-w-0 text-left" x-text="label()"></span>
        <svg class="header-filter-select-chevron" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
        </svg>
    </button>

    <div class="header-filter-dropdown-panel" x-show="open" x-cloak x-transition style="max-height: 280px; overflow-y: auto; width: 100%;">
        <template x-for="opt in options" :key="opt.value">
            <button type="button" class="header-filter-dropdown-option"
                :class="{ 'is-selected': opt.value === value }"
                @click="select(opt)"
                x-text="opt.label">
            </button>
        </template>
    </div>
</div>
