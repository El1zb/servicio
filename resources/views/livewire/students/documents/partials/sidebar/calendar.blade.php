{{-- Calendario Interactivo --}}
<div class="relative bg-[var(--color-card-bg)] rounded-2xl p-4 w-full select-none cursor-default"
     x-data="{
        currentDate: new Date(),
        selectedDate: null,
        selectedEvents: [],
        calendarEvents: {{ json_encode($calendarEvents) }},

        init() {
            Livewire.on('calendar-updated', (data) => {
                this.calendarEvents = data.calendarEvents;
            });
        },

        get currentMonth() { return this.currentDate.toLocaleString('es-MX', { month: 'long' }); },
        get currentYear()  { return this.currentDate.getFullYear(); },

        get daysInMonth() {
            const year = this.currentDate.getFullYear();
            const month = this.currentDate.getMonth();
            const firstDay = new Date(year, month, 1).getDay();
            const total = new Date(year, month + 1, 0).getDate();
            const days = [];
            for (let i = 0; i < firstDay; i++) days.push(null);
            for (let day = 1; day <= total; day++) {
                const dateStr = `${year}-${String(month+1).padStart(2,'0')}-${String(day).padStart(2,'0')}`;
                days.push({ day, dateStr, events: this.getEventsForDate(dateStr), isToday: this.isToday(year, month, day) });
            }
            return days;
        },

        getEventsForDate(dateStr) { return this.calendarEvents.filter(e => e.date === dateStr); },

        isToday(year, month, day) {
            const t = new Date();
            return t.getFullYear() === year && t.getMonth() === month && t.getDate() === day;
        },

        prevMonth() { this.currentDate = new Date(this.currentDate.getFullYear(), this.currentDate.getMonth() - 1); },
        nextMonth() { this.currentDate = new Date(this.currentDate.getFullYear(), this.currentDate.getMonth() + 1); },

        selectDay(day) {
            if (day && day.events.length > 0) {
                if (this.selectedDate === day.dateStr) {
                    this.selectedDate = null;
                    this.selectedEvents = [];
                } else {
                    this.selectedDate   = day.dateStr;
                    this.selectedEvents = day.events;
                }
            }
        },

        getDayColor(events) {
            if (!events.length) return '';
            // Prioridad: rechazado > vencido > en_revision sin archivo > pendiente > en_revision con archivo > revisado
            if (events.some(e => e.status === 'rechazado'))                                    return 'bg-red-500';
            if (events.some(e => e.isExpired && !e.hasFile && e.status !== 'revisado'))        return 'bg-gray-400';
            if (events.some(e => e.status === 'en_revision' && !e.hasFile))                   return 'bg-yellow-400';
            if (events.some(e => !e.hasFile && e.status !== 'revisado' && !e.isExpired))       return 'bg-yellow-400';
            if (events.some(e => e.status === 'en_revision' && e.hasFile))                    return 'bg-blue-500';
            if (events.some(e => e.status === 'revisado'))                                    return 'bg-green-500';
            return 'bg-yellow-400';
        },

        getDayRing(events) {
            if (!events.length) return '';
            if (events.some(e => e.status === 'rechazado'))                             return 'ring-1 ring-red-500/40';
            if (events.some(e => e.isExpired && !e.hasFile && e.status !== 'revisado')) return 'ring-1 ring-gray-400/40';
            if (events.some(e => e.status === 'en_revision' && e.hasFile))             return 'ring-1 ring-blue-500/40';
            if (events.some(e => e.status === 'revisado'))                             return 'ring-1 ring-green-500/40';
            return 'ring-1 ring-yellow-400/40';
        }
    }">

    {{-- Header: mes + flechas --}}
    <div class="flex items-center justify-between mb-4">
        <button @click="prevMonth()"
                class="w-8 h-8 flex items-center justify-center rounded-full
                       text-[var(--color-secondary)] hover:text-[var(--color-text)]
                       hover:bg-[var(--color-icon-bg)] transition-all">
            <i class="fas fa-chevron-left text-xs"></i>
        </button>

        <div class="text-center">
            <p class="text-base font-bold text-[var(--color-primary)] capitalize tracking-wide" x-text="currentMonth"></p>
            <p class="text-xs text-[var(--color-secondary)] tabular-nums" x-text="currentYear"></p>
        </div>

        <button @click="nextMonth()"
                class="w-8 h-8 flex items-center justify-center rounded-full
                       text-[var(--color-secondary)] hover:text-[var(--color-text)]
                       hover:bg-[var(--color-icon-bg)] transition-all">
            <i class="fas fa-chevron-right text-xs"></i>
        </button>
    </div>

    {{-- Cabecera días de semana --}}
    <div class="grid grid-cols-7 mb-2">
        @foreach(['D','L','M','M','J','V','S'] as $d)
            <div class="text-center text-[11px] font-bold text-[var(--color-secondary)] py-1 uppercase tracking-widest">
                {{ $d }}
            </div>
        @endforeach
    </div>

    {{-- Separador sutil --}}
    <div class="border-t border-[var(--color-border-hover)] opacity-30 mb-2"></div>

    {{-- Grid de días: celdas % del contenedor en vez de w-8 fijo --}}
    <div class="grid grid-cols-7 gap-y-1">
        <template x-for="(day, index) in daysInMonth" :key="index">
            <div class="flex items-center justify-center">
                <div x-show="day === null" class="w-full aspect-square"></div>

                <button x-show="day !== null"
                    @click="selectDay(day)"
                    :class="[
                        day?.isToday
                            ? 'bg-[var(--color-icon-bg)] text-[var(--color-primary-2)] font-bold shadow-sm'
                            : (selectedDate === day?.dateStr
                                ? 'text-[var(--color-text)]'
                                : 'text-[var(--color-text)] hover:bg-[var(--color-icon-bg)]'),
                        day?.events.length > 0 ? 'cursor-pointer' : 'cursor-default opacity-70'
                    ]"
                    class="w-full aspect-square max-w-[2.5rem] flex flex-col items-center justify-center rounded-lg relative group transition-all duration-150">

                    <span class="text-xs leading-none"
                          :class="day?.isToday ? 'text-[var-(--color-primary)]' : ''"
                          x-text="day?.day"></span>

                    {{-- Dots de eventos: ordenados por prioridad de urgencia --}}
                    <div x-show="day?.events.length > 0" class="flex gap-[3px] absolute bottom-[3px]">
                        <template x-for="event in [...(day?.events ?? [])].sort((a, b) => {
                            const priority = e => {
                                if (e.status === 'rechazado') return 0;
                                if (e.isExpired && !e.hasFile && e.status !== 'revisado') return 1;
                                if (e.status === 'en_revision' && !e.hasFile) return 2;
                                if (!e.hasFile && e.status !== 'revisado') return 3;
                                if (e.status === 'en_revision' && e.hasFile) return 4;
                                return 5;
                            };
                            return priority(a) - priority(b);
                        }).slice(0, 3)">
                            <div :class="[getDayColor([event]), day?.isToday ? 'opacity-90' : '']"
                                class="w-[5px] h-[5px] rounded-full"></div>
                        </template>
                    </div>

                    {{-- Tooltip --}}
                    <div x-show="day?.events.length > 0"
                         class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 px-2.5 py-1.5
                                bg-[var(--color-icon-bg-hover)] text-[var(--color-icon-hover)]
                                text-xs rounded-lg shadow-lg
                                opacity-0 group-hover:opacity-100 transition-all duration-150
                                pointer-events-none whitespace-nowrap z-10 font-medium">
                        <span x-text="day?.events.length + ' doc' + (day?.events.length > 1 ? 's' : '')"></span>
                    </div>
                </button>
            </div>
        </template>
    </div>

    {{-- Leyenda --}}
    <div class="mt-4 pt-3 border-t border-[var(--color-border-hover)] opacity-80">
        <div class="grid grid-cols-2 gap-x-2 gap-y-1.5">
            <div class="flex items-center gap-1.5">
                <div class="w-2 h-2 rounded-full bg-green-500 flex-shrink-0"></div>
                <span class="text-xs text-[var(--color-secondary)]">Aprobado</span>
            </div>
            <div class="flex items-center gap-1.5">
                <div class="w-2 h-2 rounded-full bg-blue-500 flex-shrink-0"></div>
                <span class="text-xs text-[var(--color-secondary)]">En revisión</span>
            </div>
            <div class="flex items-center gap-1.5">
                <div class="w-2 h-2 rounded-full bg-red-500 flex-shrink-0"></div>
                <span class="text-xs text-[var(--color-secondary)]">Rechazado</span>
            </div>
            <div class="flex items-center gap-1.5">
                <div class="w-2 h-2 rounded-full bg-yellow-400 flex-shrink-0"></div>
                <span class="text-xs text-[var(--color-secondary)]">Pendiente</span>
            </div>
            <div class="flex items-center gap-1.5">
                <div class="w-2 h-2 rounded-full bg-gray-400 flex-shrink-0"></div>
                <span class="text-xs text-[var(--color-secondary)]">Vencido</span>
            </div>
        </div>
    </div>

    {{-- Eventos del día seleccionado --}}
    <div x-show="selectedEvents.length > 0"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 translate-y-1"
         x-transition:enter-end="opacity-100 translate-y-0"
         class="mt-4 pt-3 border-t border-[var(--color-border-hover)]">

        <div class="flex items-center justify-between mb-2.5">
            <div>
                <p class="text-xs font-bold uppercase tracking-widest text-[var(--color-secondary)]">Documentos</p>
                <p class="text-sm font-semibold text-[var(--color-text)]"
                   x-text="(() => {
                       if (!selectedDate) return '';
                       const p = selectedDate.split('-');
                       return new Date(+p[0], +p[1]-1, +p[2]).toLocaleDateString('es-MX', { day:'numeric', month:'long' });
                   })()">
                </p>
            </div>
            <button @click="selectedDate = null; selectedEvents = []"
                    class="w-6 h-6 flex items-center justify-center rounded-full
                           text-[var(--color-secondary)] hover:text-[var(--color-text)]
                           hover:bg-[var(--color-icon-bg)] transition-all text-xs">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <div class="space-y-1.5 max-h-36 overflow-y-auto sidebar-scroll">
            <template x-for="event in selectedEvents" :key="event.doc.id">
                <div class="flex items-center justify-between px-2.5 py-2 rounded-lg">
                    <p class="text-xs font-medium text-[var(--color-text)] truncate pr-2" x-text="event.doc.name"></p>
                    <span class="text-[10px] font-bold flex-shrink-0 px-1.5 py-0.5 rounded-full bg-[var(--color-icon-bg)] text-[var(--color-secondary)]"
                        x-text="(() => {
                            if (event.status === 'revisado')                     return 'APROBADO';
                            if (event.status === 'rechazado')                    return 'RECHAZADO';
                            if (event.isExpired && !event.hasFile)               return 'VENCIDO';
                            if (event.status === 'en_revision' && event.hasFile) return 'REVISIÓN';
                            return 'PENDIENTE';
                        })()">
                    </span>
                </div>
            </template>
        </div>
    </div>
</div>