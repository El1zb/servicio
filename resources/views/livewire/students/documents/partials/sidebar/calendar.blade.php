{{-- Calendario Interactivo --}}
<div class="bg-[var(--index-card-bg)] rounded-xl shadow-lg border border-[var(--index-border)] relative"
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
                this.selectedDate   = day.dateStr;
                this.selectedEvents = day.events;
            }
        },

        getDayColor(events) {
            if (!events.length) return '';
            if (events.some(e => e.status === 'rechazado'))                                       return 'bg-red-500';
            if (events.some(e => e.isExpired && !e.hasFile && e.status !== 'revisado'))            return 'bg-orange-400';
            if (events.some(e => e.status === 'en_revision' && e.hasFile))                        return 'bg-blue-500';
            if (events.some(e => e.status === 'en_revision' && !e.hasFile))                       return 'bg-yellow-400';
            if (events.some(e => e.status === 'revisado'))                                        return 'bg-green-500';
            return 'bg-yellow-400';
        }
    }">

    {{-- Header --}}
    <div class="bg-gradient-to-r from-[var(--index-content-bg)] to-[var(--index-content-bg)] p-4 rounded-t-xl">
        <div class="flex items-center justify-between mb-3">
            <button @click="prevMonth()" class="w-8 h-8 flex items-center justify-center rounded-lg text-white hover:scale-125 transition-transform duration-200">
                <i class="fas fa-chevron-left text-xs"></i>
            </button>
            <div class="text-center">
                <h3 class="text-base font-bold text-white capitalize" x-text="currentMonth"></h3>
                <p class="text-xs text-white/70" x-text="currentYear"></p>
            </div>
            <button @click="nextMonth()" class="w-8 h-8 flex items-center justify-center rounded-lg text-white hover:scale-125 transition-transform duration-200">
                <i class="fas fa-chevron-right text-xs"></i>
            </button>
        </div>
        <div class="flex flex-wrap justify-center gap-x-3 gap-y-1 text-xs">
            <div class="flex items-center gap-1"><div class="w-2 h-2 rounded-full bg-green-500"></div><span class="text-white/80">Aprobado</span></div>
            <div class="flex items-center gap-1"><div class="w-2 h-2 rounded-full bg-blue-500"></div><span class="text-white/80">En revisión</span></div>
            <div class="flex items-center gap-1"><div class="w-2 h-2 rounded-full bg-red-500"></div><span class="text-white/80">Rechazado</span></div>
            <div class="flex items-center gap-1"><div class="w-2 h-2 rounded-full bg-yellow-400"></div><span class="text-white/80">Pendiente</span></div>
            <div class="flex items-center gap-1"><div class="w-2 h-2 rounded-full bg-orange-400"></div><span class="text-white/80">Vencido</span></div>
        </div>
    </div>

    {{-- Grid --}}
    <div class="p-4">
        <div class="grid grid-cols-7 gap-1 mb-2">
            @foreach(['Dom','Lun','Mar','Mié','Jue','Vie','Sáb'] as $d)
                <div class="text-center text-xs font-bold text-[var(--index-text-secondary)] py-2">{{ $d }}</div>
            @endforeach
        </div>
        <div class="grid grid-cols-7 gap-1">
            <template x-for="(day, index) in daysInMonth" :key="index">
                <div>
                    <button x-show="day !== null" @click="selectDay(day)"
                            :class="{
                                'bg-[var(--index-icon-bg)] border-2 border-[var(--index-accent)]': day?.isToday,
                                'hover:bg-[var(--index-content-bg)]': day?.events.length === 0,
                                'cursor-pointer hover:scale-105': day?.events.length > 0,
                                'opacity-50': day?.events.length === 0
                            }"
                            class="w-full aspect-square flex flex-col items-center justify-center rounded-md transition-all relative group">
                        <span class="text-sm font-medium text-[var(--index-text-primary)]" x-text="day?.day"></span>
                        <div x-show="day?.events.length > 0" class="flex gap-0.5 mt-1">
                            <template x-for="event in day?.events.slice(0, 3)">
                                <div :class="getDayColor([event]) + ' w-1 h-1 rounded-full'"></div>
                            </template>
                        </div>
                        <div x-show="day?.events.length > 0"
                             class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 px-2 py-1 bg-gray-900 text-white text-xs rounded opacity-0 group-hover:opacity-100 transition pointer-events-none whitespace-nowrap z-10">
                            <span x-text="day?.events.length + ' documento(s)'"></span>
                        </div>
                    </button>
                </div>
            </template>
        </div>
    </div>

    {{-- Eventos del día seleccionado --}}
    <div x-show="selectedEvents.length > 0" x-transition
         class="border-t border-[var(--index-border)] p-4 bg-[var(--index-content-bg)] rounded-b-xl">
        <div class="flex items-center justify-between mb-3">
            <h4 class="font-bold text-[var(--index-text-primary)] text-sm">
                Documentos del día
                <span x-text="(() => {
                    if (!selectedDate) return '';
                    const p = selectedDate.split('-');
                    return new Date(+p[0], +p[1]-1, +p[2]).toLocaleDateString('es-MX', { weekday:'long', day:'numeric', month:'long', year:'numeric' });
                })()"></span>
            </h4>
            <button @click="selectedDate = null; selectedEvents = []"
                    class="w-8 h-8 flex items-center justify-center rounded-full text-[var(--index-text-secondary)] hover:text-[var(--index-text-primary)] transition">
                <i class="fas fa-times text-base"></i>
            </button>
        </div>
        <div class="space-y-2 max-h-48 overflow-y-auto sidebar-scroll">
            <template x-for="event in selectedEvents" :key="event.doc.id">
                <div class="p-3 rounded-lg transition-all hover:shadow-md bg-[var(--index-card-bg)] border border-[var(--index-border)]">
                    <p class="text-xs font-semibold text-[var(--index-text-primary)]" x-text="event.doc.name"></p>
                    <span class="text-[var(--index-text-secondary)] text-[10px] font-bold"
                          x-text="(() => {
                            if (event.status === 'revisado')   return 'APROBADO';
                            if (event.status === 'rechazado')  return 'RECHAZADO';
                            if (event.isExpired && !event.hasFile) return 'VENCIDO';
                            if (event.status === 'en_revision' && event.hasFile)  return 'EN REVISIÓN';
                            return 'PENDIENTE';
                          })()">
                    </span>
                </div>
            </template>
        </div>
    </div>
</div>