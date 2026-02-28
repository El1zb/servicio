{{-- REVISIÓN DE DOCUMENTOS --}}
<div class="backdrop-blur-xl rounded-2xl shadow-2xl overflow-hidden"
     style="background-color: var(--period-detail-bg); border: 1px solid var(--period-detail-border);">

    {{-- Header --}}
    <div class="p-4 sm:p-6" style="border-bottom: 1px solid var(--period-detail-border);">
        <h2 class="text-xl sm:text-2xl font-bold mb-2" style="color: var(--period-detail-text-primary);">
            Revisión de Documentos
        </h2>
        <p style="color: var(--period-detail-text-secondary);">
            Revisa los documentos entregados por los estudiantes
        </p>

        <div class="mt-4 flex flex-col gap-3">

            {{-- Fila 1: Búsqueda + Exportar --}}
            <div class="flex gap-3 items-center">
                <div class="flex-1 min-w-0">
                    <div class="relative w-full">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                style="color: var(--period-detail-text-primary);">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>
                        <input
                            type="text"
                            wire:model.live.debounce.300ms="searchRevision"
                            placeholder="Buscar por nombre o número de control..."
                            class="w-full pl-10 pr-4 py-2.5 rounded-lg text-sm transition-all focus:ring-2 focus:outline-none"
                            style="background-color: var(--period-detail-card-bg);
                                   color: var(--period-detail-text-primary);
                                   border: 1px solid var(--period-detail-border);"
                        >
                    </div>
                </div>

                {{-- Exportar Excel --}}
                <div class="relative group/excel shrink-0">
                    <button
                        wire:click="exportExcel"
                        wire:loading.attr="disabled"
                        class="px-4 sm:px-5 py-2.5 font-semibold rounded-lg shadow-sm transition-all disabled:opacity-50 disabled:cursor-not-allowed flex items-center gap-2 whitespace-nowrap"
                        style="background: var(--period-detail-bg); color: var(--period-detail-btn-text);"
                        onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 20px 25px -5px rgba(0, 0, 0, 0.3)';"
                        onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 10px 15px -3px rgba(0, 0, 0, 0.3)';"
                    >
                        <span wire:loading.remove wire:target="exportExcel" class="flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </span>
                        <span wire:loading wire:target="exportExcel" class="flex items-center gap-2">
                            <svg class="animate-spin h-5 w-5" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Procesando...
                        </span>
                    </button>
                    <div class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 px-2 py-1 text-xs font-medium rounded-lg opacity-0 group-hover/excel:opacity-100 transition-all duration-200 pointer-events-none whitespace-nowrap z-50"
                        style="background: linear-gradient(135deg, var(--period-detail-brand-primary) 0%, var(--period-detail-brand-secondary) 100%); color: var(--period-detail-text-icon);">
                        Exportar Excel
                        <div class="absolute top-full left-1/2 -translate-x-1/2 border-4 border-transparent"
                            style="border-top-color: var(--period-detail-brand-secondary);"></div>
                    </div>
                </div>
            </div>

            {{-- Fila 2: Filtros --}}
            <div class="flex flex-col sm:flex-row gap-3">
                <flux:select wire:model.live="careerFilter"
                    class="w-full sm:flex-1 px-3 py-2.5 rounded-lg text-sm font-medium transition-all"
                    style="background-color: var(--period-detail-card-bg);
                           color: var(--period-detail-text-primary);
                           border: 1px solid var(--period-detail-border);">
                    <option value="">Todas las carreras</option>
                    @foreach($careers as $career)
                        <option value="{{ $career->id }}">{{ $career->name }}</option>
                    @endforeach
                </flux:select>

                <flux:select wire:model.live="statusFilter"
                    class="w-full sm:w-48 px-3 py-2.5 rounded-lg text-sm font-medium transition-all"
                    style="background-color: var(--period-detail-card-bg);
                           color: var(--period-detail-text-primary);
                           border: 1px solid var(--period-detail-border);">
                    <option value="">Todos</option>
                    <option value="pending">Pendientes</option>
                    <option value="approved">Aprobados</option>
                    <option value="rejected">Rechazados</option>
                </flux:select>
            </div>

        </div>
    </div>

    {{-- Tabla: sm en adelante --}}
    <div class="hidden sm:block overflow-x-auto">
        <table class="w-full">
            <thead style="background: linear-gradient(135deg, var(--period-detail-card-header-approved-bg) 0%, var(--period-detail-card-header-approved-bg-2) 100%);">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-semibold uppercase w-12"
                        style="color: var(--period-detail-text-secondary);"></th>
                    <th class="px-6 py-4 text-left text-xs font-semibold uppercase"
                        style="color: var(--period-detail-text-secondary);">Estudiante</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold uppercase"
                        style="color: var(--period-detail-text-secondary);">Carrera</th>
                    <th class="px-6 py-4 text-center text-xs font-semibold uppercase"
                        style="color: var(--period-detail-text-secondary);">Aprobados</th>
                    <th class="px-6 py-4 text-center text-xs font-semibold uppercase"
                        style="color: var(--period-detail-text-secondary);">Pendientes</th>
                    <th class="px-6 py-4 text-center text-xs font-semibold uppercase"
                        style="color: var(--period-detail-text-secondary);">Rechazados</th>
                    <th class="px-6 py-4 text-center text-xs font-semibold uppercase"
                        style="color: var(--period-detail-text-secondary);">Seguimiento</th>
                </tr>
            </thead>

            <tbody style="border-top: 1px solid var(--period-detail-border);">
                @forelse($studentsRevision as $student)
                    <tr
                        class="transition hover-row cursor-pointer"
                        wire:click="toggleStudentExpand({{ $student->id }})"
                        onmouseover="this.style.backgroundColor='var(--period-detail-card-bg)'"
                        onmouseout="this.style.backgroundColor='transparent'"
                        style="border-bottom: 1px solid var(--period-detail-border);">

                        <td class="px-6 py-4">
                            <div class="p-1.5 rounded-lg transition"
                                 style="background-color: var(--period-detail-card-bg);">
                                <svg class="w-5 h-5 transition-transform {{ $expandedStudent === $student->id ? 'rotate-90' : '' }}"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                    style="color: var(--period-detail-text-secondary);">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </div>
                        </td>

                        <td class="px-6 py-4">
                            <p class="font-medium" style="color: var(--period-detail-text-primary);">
                                {{ $student->name }} {{ $student->last_name_paterno }} {{ $student->last_name_materno }}
                            </p>
                            <p class="text-xs" style="color: var(--period-detail-text-secondary);">{{ $student->control_number }}</p>
                            <p class="text-xs" style="color: var(--period-detail-text-secondary);">{{ $student->personal_email }}</p>
                        </td>

                        <td class="px-6 py-4">
                            <span class="text-sm" style="color: var(--period-detail-text-primary);">
                                {{ Str::limit($student->career->name ?? 'N/A', 30) }}
                            </span>
                        </td>

                        <td class="px-6 py-4 text-center">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-sm font-semibold"
                                style="background-color: rgba(16, 185, 129, 0.2); color: var(--period-detail-status-approved-icon-color);">
                                {{ $student->approved_count ?? 0 }}
                            </span>
                        </td>

                        <td class="px-6 py-4 text-center">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-sm font-semibold"
                                style="background-color: rgba(232, 210, 50, 0.2); color: var(--period-detail-status-pending);">
                                {{ $student->pending_count ?? 0 }}
                            </span>
                        </td>

                        <td class="px-6 py-4 text-center">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-sm font-semibold"
                                style="background-color: rgba(239, 68, 68, 0.2); color: var(--period-detail-status-rejected-text);">
                                {{ $student->rejected_count ?? 0 }}
                            </span>
                        </td>

                        <td class="px-6 py-4">
                            <div class="flex items-center justify-center gap-2" onclick="event.stopPropagation()">
                                <div class="relative group/pdf-seguimiento">
                                    <button
                                        wire:click="exportStudentPDF({{ $student->id }})"
                                        class="w-8 h-8 flex items-center justify-center rounded-lg transition-all duration-200 cursor-pointer text-xs hover:-translate-y-0.5"
                                        style="background-color: var(--period-detail-btn-bg-g); color: var(--period-detail-btn-text-g);"
                                        onmouseover="this.style.transform='translateY(-2px)'; this.style.backgroundColor='var(--period-detail-btn-hover-g)'; this.style.color='var(--period-detail-btn-text-g)';"
                                        onmouseout="this.style.transform='translateY(0)'; this.style.backgroundColor='var(--period-detail-btn-bg-g)'; this.style.color='var(--period-detail-btn-text-g)';"
                                    >
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                        </svg>
                                    </button>
                                    <div class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 px-2 py-1 text-xs font-medium rounded-lg opacity-0 group-hover/pdf-seguimiento:opacity-100 transition-all duration-200 pointer-events-none whitespace-nowrap z-50"
                                        style="background-color: var(--period-detail-btn-bg-g); color: var(--period-detail-btn-text-g); border: 1px solid var(--period-detail-border);">
                                        Descargar
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>

                    @if($expandedStudent === $student->id)
                        <tr style="background-color: var(--period-detail-card-bg);">
                            <td colspan="7" class="p-0">
                                @include('livewire.dashboard.period.partials.documents-grid', ['student' => $student])
                            </td>
                        </tr>
                    @endif

                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-16 text-center">
                            <div class="flex flex-col items-center">
                                <svg class="w-16 h-16 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                    style="color: var(--period-detail-text-secondary); opacity: 0.4;">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                                </svg>
                                <p class="font-semibold mb-1" style="color: var(--period-detail-text-secondary);">No se encontraron estudiantes</p>
                                <p class="text-sm" style="color: var(--period-detail-text-secondary); opacity: 0.7;">Intenta con otros filtros de búsqueda</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Cards: móvil --}}
    <div class="sm:hidden divide-y" style="border-color: var(--period-detail-border);">
        @forelse($studentsRevision as $student)
            <div class="p-4">
                <div class="flex items-start justify-between gap-3 cursor-pointer"
                     wire:click="toggleStudentExpand({{ $student->id }})">
                    <div class="min-w-0">
                        <p class="font-medium text-sm truncate" style="color: var(--period-detail-text-primary);">
                            {{ $student->name }} {{ $student->last_name_paterno }} {{ $student->last_name_materno }}
                        </p>
                        <p class="text-xs mt-0.5" style="color: var(--period-detail-text-secondary);">{{ $student->control_number }}</p>
                        <p class="text-xs" style="color: var(--period-detail-text-secondary);">{{ $student->career->name ?? 'N/A' }}</p>
                    </div>
                    <div class="p-1.5 rounded-lg shrink-0" style="background-color: var(--period-detail-card-bg);">
                        <svg class="w-5 h-5 transition-transform {{ $expandedStudent === $student->id ? 'rotate-90' : '' }}"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            style="color: var(--period-detail-text-secondary);">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </div>
                </div>

                <div class="mt-3 flex items-center gap-2">
                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-semibold"
                        style="background-color: rgba(16, 185, 129, 0.2); color: var(--period-detail-status-approved-icon-color);">
                        {{ $student->approved_count ?? 0 }}
                    </span>
                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-semibold"
                        style="background-color: rgba(232, 210, 50, 0.2); color: var(--period-detail-status-pending);">
                        {{ $student->pending_count ?? 0 }}
                    </span>
                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-semibold"
                        style="background-color: rgba(239, 68, 68, 0.2); color: var(--period-detail-status-rejected-text);">
                        {{ $student->rejected_count ?? 0 }}
                    </span>
                    <div class="ml-auto" onclick="event.stopPropagation()">
                        <button
                            wire:click="exportStudentPDF({{ $student->id }})"
                            class="w-8 h-8 flex items-center justify-center rounded-lg transition-all duration-200"
                            style="background-color: var(--period-detail-btn-bg-g); color: var(--period-detail-btn-text-g);"
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                            </svg>
                        </button>
                    </div>
                </div>

                @if($expandedStudent === $student->id)
                    <div class="-mx-4 mt-3">
                        @include('livewire.dashboard.period.partials.documents-grid', ['student' => $student])
                    </div>
                @endif
            </div>
        @empty
            <div class="px-6 py-16 text-center">
                <div class="flex flex-col items-center">
                    <svg class="w-16 h-16 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        style="color: var(--period-detail-text-secondary); opacity: 0.4;">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                    </svg>
                    <p class="font-semibold mb-1" style="color: var(--period-detail-text-secondary);">No se encontraron estudiantes</p>
                    <p class="text-sm" style="color: var(--period-detail-text-secondary); opacity: 0.7;">Intenta con otros filtros de búsqueda</p>
                </div>
            </div>
        @endforelse
    </div>

    @if($studentsRevision->hasPages())
        <div class="p-4" style="border-top: 1px solid var(--period-detail-border);">
            {{ $studentsRevision->links() }}
        </div>
    @endif

</div>