{{-- Selector de fecha personalizado: mismo estilo que mis combobox (blanco,
     borde gris, redondeado), con calendario propio (mes/año, días,
     "Quitar fecha") en vez del selector nativo del navegador. --}}
@props(['wireModel', 'value' => null, 'min' => null, 'max' => null, 'overlay' => false, 'placeholder' => 'Sin fecha límite'])

<div {{ $attributes->merge(['class' => 'relative']) }}
    wire:ignore
    x-data="{
        value: @js($value),
        overlay: @js($overlay),
        open: false,
        openSide: 'left',
        fixedTop: 0,
        fixedLeft: 0,
        viewYear: 0,
        viewMonth: 0,
        min: @js($min),
        max: @js($max),
        monthNames: ['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'],
        label() {
            if (! this.value) return @js($placeholder);
            const [y, m, d] = this.value.split('-');
            return d + '/' + m + '/' + y;
        },
        setView(iso) {
            const [y, m] = iso.split('-');
            this.viewYear = parseInt(y);
            this.viewMonth = parseInt(m) - 1;
        },
        // El posicionamiento del panel se aplica de forma imperativa (no vía :style
        // reactivo) porque el binding declarativo deja de reaccionar tras un morph
        // de Livewire (p. ej. después de guardar o de una validación fallida),
        // dejando el panel congelado con los valores de su primera evaluación.
        hidePanel() {
            this.open = false;
            this.$refs.panel.style.display = 'none';
        },
        showPanel() {
            const panel = this.$refs.panel;
            panel.style.display = 'block';
            panel.style.width = '264px';
            panel.style.padding = '12px';

            if (this.overlay) {
                panel.style.position = 'fixed';
                panel.style.top = this.fixedTop + 'px';
                panel.style.left = this.fixedLeft + 'px';
                panel.style.right = 'auto';
                panel.style.zIndex = 9999;
            } else {
                panel.style.position = 'absolute';
                panel.style.top = 'calc(100% + 4px)';
                panel.style.left = this.openSide === 'left' ? '0' : 'auto';
                panel.style.right = this.openSide === 'right' ? '0' : 'auto';
                panel.style.zIndex = 50;
            }

            this.open = true;
        },
        toggle() {
            if (this.open) { this.hidePanel(); return; }
            this.setView(this.value || this.min || new Date().toISOString().slice(0, 10));
            const rect = this.$refs.trigger.getBoundingClientRect();
            const margin = 8;
            const panelWidth = 264;
            const fitsRight = (rect.left + panelWidth + margin) <= window.innerWidth;

            if (this.overlay) {
                // Modo overlay: position:fixed relativo al viewport, siempre abre hacia
                // abajo y se sobrepone a todo (para columnas con overflow-y-auto que
                // recortarían un panel absoluto, como el visor de revisión).
                this.fixedLeft = fitsRight ? rect.left : Math.max(margin, rect.right - panelWidth);
                this.fixedTop = rect.bottom + margin;
            } else {
                // Modo contenido: position:absolute anclado al trigger, se mueve con el
                // scroll de la página, siempre abre hacia abajo y decide de qué lado
                // según el espacio disponible (usado en Documentos Base).
                this.openSide = fitsRight ? 'left' : 'right';
            }

            this.showPanel();
        },
        prevMonth() {
            this.viewMonth--;
            if (this.viewMonth < 0) { this.viewMonth = 11; this.viewYear--; }
        },
        nextMonth() {
            this.viewMonth++;
            if (this.viewMonth > 11) { this.viewMonth = 0; this.viewYear++; }
        },
        daysInMonth() {
            return new Date(this.viewYear, this.viewMonth + 1, 0).getDate();
        },
        leadingBlanks() {
            const day = new Date(this.viewYear, this.viewMonth, 1).getDay();
            return Array.from({ length: (day + 6) % 7 });
        },
        isoFor(day) {
            const mm = String(this.viewMonth + 1).padStart(2, '0');
            const dd = String(day).padStart(2, '0');
            return this.viewYear + '-' + mm + '-' + dd;
        },
        isDisabled(day) {
            const iso = this.isoFor(day);
            return (this.min && iso < this.min) || (this.max && iso > this.max);
        },
        isSelected(day) {
            return this.value === this.isoFor(day);
        },
        selectDay(day) {
            if (this.isDisabled(day)) return;
            this.value = this.isoFor(day);
            $wire.set('{{ $wireModel }}', this.value);
            this.$dispatch('date-change', this.value);
            this.hidePanel();
        },
        clearDate() {
            if (! this.value) return;
            this.value = null;
            $wire.set('{{ $wireModel }}', null);
            this.$dispatch('date-change', null);
            this.hidePanel();
        }
    }"
    x-init="open = false; $refs.panel.style.display = 'none';"
    @click.outside="if (open) hidePanel()">
    <button type="button" x-ref="trigger" class="header-filter-select review-field w-full" @click="toggle()">
        <span x-text="label()"></span>
        <svg class="header-filter-select-chevron" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
        </svg>
    </button>

    <div class="header-filter-dropdown-panel" x-ref="panel" style="display: none;">
        <div class="flex items-center justify-between mb-2">
            <button type="button" class="review-icon-btn" style="width: 26px; height: 26px;" @click.stop="prevMonth()">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 18l-6-6 6-6"/></svg>
            </button>
            <span class="text-xs font-semibold" style="color: var(--color-primary-2);" x-text="monthNames[viewMonth] + ' ' + viewYear"></span>
            <button type="button" class="review-icon-btn" style="width: 26px; height: 26px;" @click.stop="nextMonth()">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 18l6-6-6-6"/></svg>
            </button>
        </div>

        <div class="grid grid-cols-7 gap-0.5 mb-1">
            <template x-for="d in ['L','M','M','J','V','S','D']">
                <div class="text-center text-xs" style="color: var(--color-secondary);" x-text="d"></div>
            </template>
        </div>

        <div class="grid grid-cols-7 gap-0.5">
            <template x-for="(blank, i) in leadingBlanks()" :key="'b' + i">
                <div></div>
            </template>
            <template x-for="day in daysInMonth()" :key="day">
                <button type="button"
                    class="review-calendar-day"
                    :class="{ 'is-selected': isSelected(day) }"
                    :disabled="isDisabled(day)"
                    @click.stop="selectDay(day)"
                    x-text="day">
                </button>
            </template>
        </div>

        <button type="button" x-show="value" @click.stop="clearDate()"
            class="text-xs mt-2"
            style="color: var(--color-secondary);">
            Quitar fecha
        </button>
    </div>
</div>
