{{-- Buscador: en desktop vive en el topbar, en mobile se queda acá. --}}
@push('topbar-search')
    <div class="topbar-search-input-wrap">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16.6725 16.6412L21 21"/>
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11C19 15.4183 15.4183 19 11 19C6.58172 19 3 15.4183 3 11C3 6.58172 6.58172 3 11 3C15.4183 3 19 6.58172 19 11Z"/>
        </svg>
        <input type="text" placeholder="Buscar por nombre o número de control..." value="{{ $searchRevision }}" oninput="topbarSearchInput(this.value, 'searchRevision')" class="topbar-search-input"/>
    </div>
@endpush

{{-- Botón principal: negro sólido, igual que "Nuevo X". --}}
@push('topbar-actions')
    <button type="button" wire:loading.attr="disabled" wire:target="exportExcel"
            onclick="topbarAction('exportExcel')"
            class="header-filter-select disabled:opacity-40 disabled:cursor-not-allowed">
        <span wire:loading.remove wire:target="exportExcel">Exportar a Excel</span>
        <span wire:loading wire:target="exportExcel">Exportando...</span>
    </button>

    <button type="button" onclick="topbarAction('reviewAllPending')"
            @disabled($pendingDocsCount === 0)
            class="btn-primary disabled:opacity-40 disabled:cursor-not-allowed">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M13 5l7 7-7 7M5 5l7 7-7 7"/></svg>
        Revisar pendientes
    </button>
@endpush

<div class="space-y-6">

    {{-- Sin respaldo mobile: "Revisar pendientes" queda oculto en mobile
         (solo vive en el topbar de escritorio, ver @push arriba). --}}

    {{-- Filtros: carrera, estatus y exportar --}}
    <div class="flex flex-wrap items-center gap-3">
        <div class="header-filter-dropdown" wire:ignore
             x-data="{ open: false, value: '{{ $careerFilter }}', label: '{{ $careerFilter ? ($filterCareers->firstWhere('id', $careerFilter)->name ?? 'Todas las carreras') : 'Todas las carreras' }}' }"
             @click.outside="open = false">
            <button type="button" class="header-filter-select" @click="open = !open">
                <span x-text="label"></span>
                <svg class="header-filter-select-chevron" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </button>

            <div class="header-filter-dropdown-panel" x-show="open" x-cloak x-transition style="max-height: 280px; overflow-y: auto;">
                <button type="button" class="header-filter-dropdown-option" :class="{ 'is-selected': value === '' }"
                        @click="value = ''; label = 'Todas las carreras'; open = false; $wire.set('careerFilter', null)">
                    Todas las carreras
                </button>
                @foreach($filterCareers as $career)
                    <button type="button" class="header-filter-dropdown-option" :class="{ 'is-selected': value === '{{ $career->id }}' }"
                            @click="value = '{{ $career->id }}'; label = '{{ $career->name }}'; open = false; $wire.set('careerFilter', {{ $career->id }})">
                        {{ $career->name }}
                    </button>
                @endforeach
            </div>
        </div>

        <div class="header-filter-dropdown" wire:ignore
             x-data="{ open: false, value: '{{ $statusFilter }}', label: {{ json_encode(['' => 'Todos los estatus', 'pending' => 'Pendiente', 'approved' => 'Aprobados', 'rejected' => 'Rechazados', 'missing_individual' => 'Falta individual'][$statusFilter ?? '']) }} }"
             @click.outside="open = false">
            <button type="button" class="header-filter-select" @click="open = !open">
                <span x-text="label"></span>
                <svg class="header-filter-select-chevron" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </button>

            <div class="header-filter-dropdown-panel" x-show="open" x-cloak x-transition>
                @foreach(['' => 'Todos los estatus', 'pending' => 'Pendiente', 'approved' => 'Aprobados', 'rejected' => 'Rechazados', 'missing_individual' => 'Falta individual'] as $value => $label)
                    <button type="button" class="header-filter-dropdown-option" :class="{ 'is-selected': value === '{{ $value }}' }"
                            @click="value = '{{ $value }}'; label = '{{ $label }}'; open = false; $wire.set('statusFilter', '{{ $value }}')">
                        {{ $label }}
                    </button>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Grid de estudiantes --}}
    @if($studentsRevision->count())
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5">
            @foreach($studentsRevision as $student)
                <div class="period-card group" wire:click="viewStudentDocuments({{ $student->id }})" style="cursor: pointer;">
                    <div class="flex items-start justify-between gap-2">
                        <div class="min-w-0">
                            <p class="stat-card-label truncate">
                                {{ $student->name }} {{ $student->last_name_paterno }}
                            </p>
                            <p class="text-xs truncate mt-0.5" style="color: var(--color-secondary);">
                                {{ $student->career->name ?? '—' }} · {{ $student->control_number }}
                            </p>
                        </div>

                        <button wire:click.stop="exportStudentPDF({{ $student->id }})"
                                title="Descargar seguimiento"
                                class="w-7 h-7 flex items-center justify-center rounded-full flex-shrink-0
                                       text-[var(--color-icon)] bg-transparent cursor-pointer
                                       opacity-0 group-hover:opacity-100
                                       transition-all duration-150
                                       hover:text-[var(--color-icon-hover)] hover:bg-[var(--sidebar-color-hover)]">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none">
                                <path d="M12 7L12 14M12 14L15 11M12 14L9 11" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M16 17H12H8" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                                <path d="M22 12C22 16.714 22 19.0711 20.5355 20.5355C19.0711 22 16.714 22 12 22C7.28595 22 4.92893 22 3.46447 20.5355C2 19.0711 2 16.714 2 12C2 7.28595 2 4.92893 3.46447 3.46447C4.92893 2 7.28595 2 12 2C16.714 2 19.0711 2 20.5355 3.46447C21.5093 4.43821 21.8356 5.80655 21.9449 8" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                            </svg>
                        </button>
                    </div>

                    <div class="flex items-center gap-5">
                        <div class="flex flex-col gap-0.5">
                            <span class="flex items-center gap-1.5">
                                <span class="period-card-stat-value">{{ $student->pending_count ?? 0 }}</span>
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" class="flex-shrink-0">
                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M14 22H10C6.22876 22 4.34315 22 3.17157 20.8284C2 19.6569 2 17.7712 2 14V10C2 6.22876 2 4.34315 3.17157 3.17157C4.34315 2 6.23869 2 10.0298 2C10.6358 2 11.1214 2 11.53 2.01666C11.5166 2.09659 11.5095 2.17813 11.5092 2.26057L11.5 5.09497C11.4999 6.19207 11.4998 7.16164 11.6049 7.94316C11.7188 8.79028 11.9803 9.63726 12.6716 10.3285C13.3628 11.0198 14.2098 11.2813 15.0569 11.3952C15.8385 11.5003 16.808 11.5002 17.9051 11.5001L18 11.5001H21.9574C22 12.0344 22 12.6901 22 13.5629V14C22 17.7712 22 19.6569 20.8284 20.8284C19.6569 22 17.7712 22 14 22Z" fill="var(--color-icon)"/>
                                    <path d="M19.3517 7.61665L15.3929 4.05375C14.2651 3.03868 13.7012 2.53114 13.0092 2.26562L13 5.00011C13 7.35713 13 8.53564 13.7322 9.26787C14.4645 10.0001 15.643 10.0001 18 10.0001H21.5801C21.2175 9.29588 20.5684 8.71164 19.3517 7.61665Z" fill="var(--color-icon)"/>
                                </svg>
                            </span>
                            <span class="period-card-stat-label">Pendientes</span>
                        </div>

                        @if(($student->individual_total ?? 0) > 0)
                            <div class="flex flex-col gap-0.5">
                                <span class="flex items-center gap-1.5">
                                    <span class="period-card-stat-value">{{ $student->individual_missing ?? 0 }}</span>
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" class="flex-shrink-0">
                                        <path d="M19.3517 7.61665L15.3929 4.05375C14.2651 3.03868 13.7012 2.53114 13.0092 2.26562L13 5.00011C13 7.35713 13 8.53564 13.7322 9.26787C14.4645 10.0001 15.643 10.0001 18 10.0001H21.5801C21.2175 9.29588 20.5684 8.71164 19.3517 7.61665Z" fill="var(--color-icon)"/>
                                        <path fill-rule="evenodd" clip-rule="evenodd" d="M10 22H14C17.7712 22 19.6569 22 20.8284 20.8284C22 19.6569 22 17.7712 22 14V13.5629C22 12.6901 22 12.0344 21.9574 11.5001H18L17.9051 11.5001C16.808 11.5002 15.8385 11.5003 15.0569 11.3952C14.2098 11.2813 13.3628 11.0198 12.6716 10.3285C11.9803 9.63726 11.7188 8.79028 11.6049 7.94316C11.4998 7.16164 11.4999 6.19207 11.5 5.09497L11.5092 2.26057C11.5095 2.17813 11.5166 2.09659 11.53 2.01666C11.1214 2 10.6358 2 10.0298 2C6.23869 2 4.34315 2 3.17157 3.17157C2 4.34315 2 6.22876 2 10V14C2 17.7712 2 19.6569 3.17157 20.8284C4.34315 22 6.22876 22 10 22ZM9.01296 12.9528C8.72446 12.6824 8.27554 12.6824 7.98704 12.9528L5.98704 14.8278C5.68486 15.1111 5.66955 15.5858 5.95285 15.888C6.23614 16.1901 6.71077 16.2055 7.01296 15.9222L7.75 15.2312L7.75 18.5C7.75 18.9142 8.08579 19.25 8.5 19.25C8.91421 19.25 9.25 18.9142 9.25 18.5L9.25 15.2312L9.98704 15.9222C10.2892 16.2055 10.7639 16.1901 11.0472 15.888C11.3305 15.5858 11.3151 15.1111 11.013 14.8278L9.01296 12.9528Z" fill="var(--color-icon)"/>
                                    </svg>
                                </span>
                                <span class="period-card-stat-label">Individual{{ $student->individual_missing > 1 ? 'es' : '' }}</span>
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        <div>
            {{ $studentsRevision->links() }}
        </div>
    @else
        <div class="text-center py-20">
            <svg class="w-14 h-14 mx-auto mb-4" style="color: var(--color-secondary);" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            <h3 class="text-lg font-bold mb-2" style="color: var(--color-primary-2);">
                No se encontraron estudiantes
            </h3>
            <p class="mb-6 max-w-sm mx-auto text-sm" style="color: var(--color-secondary);">
                Ajusta el buscador o los filtros para ver otros resultados.
            </p>
        </div>
    @endif

    @include('livewire.dashboard.period.modals.quick-review-modal')
</div>
