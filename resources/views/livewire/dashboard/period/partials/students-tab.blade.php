{{-- Buscador: en desktop vive en el topbar (@push), en mobile se queda acá
     (el topbar no se muestra ahí). Mismo wire:model en ambos casos. --}}
@push('topbar-search')
    <div class="topbar-search-input-wrap">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M16.6725 16.6412L21 21"/>
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M19 11C19 15.4183 15.4183 19 11 19C6.58172 19 3 15.4183 3 11C3 6.58172 6.58172 3 11 3C15.4183 3 19 6.58172 19 11Z"/>
        </svg>
        <input type="text" placeholder="Buscar por nombre o número de control..." value="{{ $search }}" oninput="topbarSearchInput(this.value)" class="topbar-search-input"/>
    </div>
@endpush

{{-- Botón principal: negro sólido, igual que "Nuevo X". Vive en el
     topbar, con respaldo en mobile más abajo. --}}
@push('topbar-actions')
    <button type="button" onclick="topbarAction('reviewPending')"
            @disabled($pendingCount === 0)
            class="btn-primary disabled:opacity-40 disabled:cursor-not-allowed">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M13 5l7 7-7 7M5 5l7 7-7 7"/></svg>
        Revisar pendientes
    </button>
@endpush

@php
    $statusFilterLabels = ['' => 'Todos los estatus', 'pending' => 'Pendientes', 'approved' => 'Aprobados', 'rejected' => 'Rechazados'];
@endphp

<div class="space-y-6">

    @include('livewire.dashboard.period.partials.stats-bar', ['stats' => $stats])

    {{-- Sin respaldo mobile: "Revisar pendientes" queda oculto en mobile
         (solo vive en el topbar de escritorio, ver @push arriba). --}}

    {{-- Filtros: estatus, carrera y semestre, los 3 juntos, mismo combobox. --}}
    <div class="flex flex-wrap gap-3">
        <div class="header-filter-dropdown" wire:ignore
             x-data="{ open: false, value: '{{ $statusFilterStudents }}', label: '{{ $statusFilterLabels[$statusFilterStudents ?? ''] ?? 'Todos los estatus' }}' }"
             @click.outside="open = false">
            <button type="button" class="header-filter-select" @click="open = !open">
                <span x-text="label"></span>
                <svg class="header-filter-select-chevron" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </button>

            <div class="header-filter-dropdown-panel" x-show="open" x-cloak x-transition>
                @foreach($statusFilterLabels as $value => $label)
                    <button type="button"
                            class="header-filter-dropdown-option"
                            :class="{ 'is-selected': value === '{{ $value }}' }"
                            @click="value = '{{ $value }}'; label = '{{ $label }}'; open = false; $wire.set('statusFilterStudents', '{{ $value }}')">
                        {{ $label }}
                    </button>
                @endforeach
            </div>
        </div>

        <div class="header-filter-dropdown" wire:ignore
             x-data="{ open: false, value: '{{ $careerFilterStudents }}', label: '{{ $careerFilterStudents ? ($filterCareers->firstWhere('id', $careerFilterStudents)->name ?? 'Todas las carreras') : 'Todas las carreras' }}' }"
             @click.outside="open = false">
            <button type="button" class="header-filter-select" @click="open = !open">
                <span x-text="label"></span>
                <svg class="header-filter-select-chevron" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </button>

            <div class="header-filter-dropdown-panel" x-show="open" x-cloak x-transition style="max-height: 280px; overflow-y: auto;">
                <button type="button" class="header-filter-dropdown-option" :class="{ 'is-selected': value === '' }"
                        @click="value = ''; label = 'Todas las carreras'; open = false; $wire.set('careerFilterStudents', null)">
                    Todas las carreras
                </button>
                @foreach($filterCareers as $career)
                    <button type="button" class="header-filter-dropdown-option" :class="{ 'is-selected': value === '{{ $career->id }}' }"
                            @click="value = '{{ $career->id }}'; label = '{{ $career->name }}'; open = false; $wire.set('careerFilterStudents', {{ $career->id }})">
                        {{ $career->name }}
                    </button>
                @endforeach
            </div>
        </div>

        <div class="header-filter-dropdown" wire:ignore
             x-data="{ open: false, value: '{{ $semesterFilterStudents }}', label: '{{ $semesterFilterStudents ? ($filterSemesters->firstWhere('id', $semesterFilterStudents)->name ?? 'Todos los semestres') : 'Todos los semestres' }}' }"
             @click.outside="open = false">
            <button type="button" class="header-filter-select" @click="open = !open">
                <span x-text="label"></span>
                <svg class="header-filter-select-chevron" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </button>

            <div class="header-filter-dropdown-panel" x-show="open" x-cloak x-transition style="max-height: 280px; overflow-y: auto;">
                <button type="button" class="header-filter-dropdown-option" :class="{ 'is-selected': value === '' }"
                        @click="value = ''; label = 'Todos los semestres'; open = false; $wire.set('semesterFilterStudents', null)">
                    Todos los semestres
                </button>
                @foreach($filterSemesters as $semester)
                    <button type="button" class="header-filter-dropdown-option" :class="{ 'is-selected': value === '{{ $semester->id }}' }"
                            @click="value = '{{ $semester->id }}'; label = '{{ $semester->name }}'; open = false; $wire.set('semesterFilterStudents', {{ $semester->id }})">
                        {{ $semester->name }}
                    </button>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Grid de estudiantes --}}
    @if($students->count())
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-3 sm:gap-5">
            @foreach($students as $student)
                {{-- Desktop: card completa (se oculta en mobile, ver .document-card-desktop en sidebar.css). --}}
                <div wire:click="openStudentCard({{ $student->id }})" class="period-card document-card-desktop" style="cursor: pointer;">
                    <div class="stat-card-top">
                        <div class="min-w-0 flex-1">
                            <p class="stat-card-label truncate">
                                {{ $student->name }} {{ $student->last_name_paterno }}
                            </p>
                            <p class="text-xs truncate mt-0.5" style="color: var(--color-secondary);">
                                {{ $student->career->name ?? '—' }}
                            </p>
                        </div>
                        <span class="status-badge {{ $student->status_badge_class }} flex-shrink-0">
                            <span class="status-badge-dot"></span>
                            {{ $student->status_label }}
                        </span>
                    </div>

                    <p class="stat-card-description" style="margin:0;">
                        <span class="stat-card-dot"></span>
                        {{ $student->control_number }} · Avance {{ $student->reticular_progress ?? 0 }}%
                    </p>
                </div>

                {{-- Mobile: mismo diseño de card que las de periodo — el estatus
                     va arriba en su propia fila, alineado a la derecha; debajo
                     va el resto de la información (nombre, carrera, etc.) tal
                     cual como en desktop. --}}
                <div wire:click="openStudentCard({{ $student->id }})" class="document-mobile-card">
                    <div class="flex items-start justify-end gap-2">
                        <span class="status-badge {{ $student->status_badge_class }}">
                            <span class="status-badge-dot"></span>
                            {{ $student->status_label }}
                        </span>
                    </div>

                    <div class="min-w-0">
                        <p class="stat-card-label truncate">
                            {{ $student->name }} {{ $student->last_name_paterno }}
                        </p>
                        <p class="text-xs truncate mt-0.5" style="color: var(--color-secondary);">
                            {{ $student->career->name ?? '—' }}
                        </p>
                    </div>

                    <p class="stat-card-description" style="margin:0;">
                        <span class="stat-card-dot"></span>
                        {{ $student->control_number }} · Avance {{ $student->reticular_progress ?? 0 }}%
                    </p>
                </div>
            @endforeach
        </div>

        <div>
            {{ $students->links() }}
        </div>
    @else
        <div class="text-center py-20">
            <svg class="w-14 h-14 mx-auto mb-4" style="color: var(--color-secondary);" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
            </svg>
            <h3 class="text-lg font-bold mb-2" style="color: var(--color-primary-2);">
                No se encontraron estudiantes
            </h3>
            <p class="mb-6 max-w-sm mx-auto text-sm" style="color: var(--color-secondary);">
                Ajusta el buscador o los filtros para ver otros resultados.
            </p>
        </div>
    @endif

    @include('livewire.dashboard.period.modals.student-modal')
    @include('livewire.dashboard.period.modals.preview-modal')
</div>
