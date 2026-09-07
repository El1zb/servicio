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

    @include('livewire.dashboard.period.partials.stats-bar', ['period' => $period])

    {{-- Respaldo mobile: buscador y botón principal (el topbar no se
         muestra en mobile). --}}
    <div class="lg:hidden flex flex-col gap-3">
        <div class="relative w-full">
            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color: var(--color-secondary);">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16.6725 16.6412L21 21"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11C19 15.4183 15.4183 19 11 19C6.58172 19 3 15.4183 3 11C3 6.58172 6.58172 3 11 3C15.4183 3 19 6.58172 19 11Z"/>
                </svg>
            </div>
            <input type="text" wire:model.live.debounce.400ms="search"
                   placeholder="Buscar por nombre o número de control..."
                   class="app-input w-full" style="padding-left: 40px;">
        </div>

        <button type="button" wire:click="reviewPending" @disabled($pendingCount === 0)
                class="btn-primary w-full disabled:opacity-40 disabled:cursor-not-allowed">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M13 5l7 7-7 7M5 5l7 7-7 7"/></svg>
            Revisar pendientes
        </button>
    </div>

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
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5">
            @foreach($students as $student)
                <div wire:click="openStudentCard({{ $student->id }})" class="period-card group" style="cursor: pointer;">
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

                    <div class="flex items-center justify-between gap-2">
                        <p class="stat-card-description" style="margin:0;">
                            <span class="stat-card-dot"></span>
                            {{ $student->control_number }} · Avance {{ $student->reticular_progress ?? 0 }}%
                        </p>

                        <button wire:click.stop="editStudent({{ $student->id }})"
                                title="Editar"
                                class="w-7 h-7 flex items-center justify-center rounded-full flex-shrink-0
                                       text-[var(--color-icon)] bg-transparent cursor-pointer
                                       opacity-0 group-hover:opacity-100
                                       transition-all duration-150
                                       hover:text-[var(--color-icon-hover)] hover:bg-[var(--sidebar-color-hover)]">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none">
                                <path d="M20.1497 7.93997L8.27971 19.81C7.21971 20.88 4.04971 21.3699 3.27971 20.6599C2.50971 19.9499 3.06969 16.78 4.12969 15.71L15.9997 3.84C16.5478 3.31801 17.2783 3.03097 18.0351 3.04019C18.7919 3.04942 19.5151 3.35418 20.0503 3.88938C20.5855 4.42457 20.8903 5.14781 20.8995 5.90463C20.9088 6.66146 20.6217 7.39189 20.0997 7.93997H20.1497Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M21 21H12" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </button>
                    </div>
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
