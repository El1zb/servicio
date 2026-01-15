<div class="space-y-8 min-h-screen p-6">

    {{-- Header --}}
    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-6 rounded-xl shadow-sm p-6" 
         style="background-color: var(--student-document-bg);">
        <div>
            <x-auth-header
                title="Periodos Académicos"
                description="Administración y seguimiento de periodos académicos."
                :center="false"
            />
        </div>
        <flux:button variant="primary" wire:click="createPeriod" icon="plus"
        class="
                        group relative
                        inline-flex items-center justify-center
                        px-4 py-2
                        rounded-[var(--radius-md)]
                        bg-[var(--modal-btn-document-descargar)]!
                        text-[var(--modal-btn-document-descargar-text)]!
                        shadow-lg shadow-[var(--modal-btn-document-descargar-shadow)]
                        hover:bg-[var(--modal-btn-document-descargar-hover)]!
                        hover:shadow-xl hover:-translate-y-0.5
                        transition-all duration-300
                        disabled:opacity-60 disabled:cursor-not-allowed
                        gap-2
                    ">
            Nuevo Periodo
        </flux:button>
    </div>

    {{-- Tarjetas de estadísticas --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5">
        {{-- Total Periodos --}}
        <div class="rounded-xl p-6 shadow-lg transition-transform duration-200 hover:scale-[1.02]" 
             style="background-color: var(--student-document-bg);">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-lg flex items-center justify-center" 
                     style="background-color: var(--student-document-bg-content-status-normal);">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" 
                         style="color: var(--student-document-text-content-status-normal-icon);">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-xs font-medium uppercase tracking-wide" style="color: var(--student-document-text-secondary);">Total Periodos</p>
                    <p class="text-2xl font-bold mt-0.5" style="color: var(--student-document-text-primary);">{{ $stats['total_periods'] }}</p>
                </div>
            </div>
        </div>

        {{-- Periodos Activos --}}
        <div class="rounded-xl p-6 shadow-lg transition-transform duration-200 hover:scale-[1.02]" 
             style="background-color: var(--student-document-bg); border: 1px solid var(--student-document-border-content);">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-lg flex items-center justify-center" 
                     style="background-color: var(--student-document-bg-content-status-approved);">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                         style="color: var(--student-document-text-content-status-approved-icon);">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-xs font-medium uppercase tracking-wide" style="color: var(--student-document-text-secondary);">Periodos Activos</p>
                    <p class="text-2xl font-bold mt-0.5" style="color: var(--student-document-text-primary);">{{ $stats['active_periods'] }}</p>
                </div>
            </div>
        </div>

        {{-- Total Estudiantes --}}
        <div class="rounded-xl p-6 shadow-lg transition-transform duration-200 hover:scale-[1.02]" 
             style="background-color: var(--student-document-bg); border: 1px solid var(--student-document-border-content);">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-lg flex items-center justify-center" 
                     style="background-color: var(--color-icon-bg);">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                         style="color: var(--color-icon-text);">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-xs font-medium uppercase tracking-wide" style="color: var(--student-document-text-secondary);">Total Estudiantes</p>
                    <p class="text-2xl font-bold mt-0.5" style="color: var(--student-document-text-primary);">{{ $stats['total_students'] }}</p>
                </div>
            </div>
        </div>

        {{-- Aprobaciones Pendientes --}}
        <div class="rounded-xl p-6 shadow-lg transition-transform duration-200 hover:scale-[1.02]" 
             style="background-color: var(--student-document-bg); border: 1px solid var(--student-document-border-content);">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-lg flex items-center justify-center" 
                     style="background-color: rgba(232, 210, 50, 0.1);">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                         style="color: var(--student-document-calendar-pending);">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-xs font-medium uppercase tracking-wide" style="color: var(--student-document-text-secondary);">Aprobaciones Pendientes</p>
                    <p class="text-2xl font-bold mt-0.5" style="color: var(--student-document-text-primary);">{{ $stats['pending_approvals'] }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="rounded-xl p-5 shadow-lg" style="background-color: var(--student-document-bg);">
    
        {{-- Filtros y búsqueda --}}
        <div class="rounded-xl p-5">
            <div class="grid grid-cols-1 md:grid-cols-12 gap-3 items-center">
                {{-- 🔍 Buscador (ocupa más espacio) --}}
                <div class="md:col-span-6 lg:col-span-7">
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                style="color: var(--student-document-text-primary);">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>

                        <input 
                            type="text"
                            placeholder="Buscar por nombre..."
                            wire:model.live="search"
                            class="w-full pl-10 pr-4 py-2.5 rounded-lg text-sm transition-all"
                            style="
                                background-color: var(--student-document-bg-card);
                                color: var(--student-document-text-primary);
                                border: 1px solid var(--student-document-border-content);
                            "
                        />
                    </div>
                </div>

                {{-- 🏷 Estado --}}
                <div class="md:col-span-3 lg:col-span-2">
                    <flux:select
                        wire:model.live="statusFilter"
                        class="w-full px-4 py-2.5 rounded-lg text-sm font-medium transition-all"
                        style="
                            background-color: var(--student-document-bg-card);
                            color: var(--student-document-text-primary);
                            border: 1px solid var(--student-document-border-content);
                        "
                    >
                        <option value="all">Todos los estados</option>
                        <option value="active">Solo activos</option>
                        <option value="inactive">Solo inactivos</option>
                    </flux:select>
                </div>

                {{-- ↕ Orden --}}
                <div class="md:col-span-3 lg:col-span-3">
                    <flux:select
                        wire:model.live="sortBy"
                        class="w-full px-4 py-2.5 rounded-lg text-sm font-medium transition-all"
                        style="
                            background-color: var(--student-document-bg-card);
                            color: var(--student-document-text-primary);
                            border: 1px solid var(--student-document-border-content);
                        "
                    >
                        <option value="recent">Más recientes</option>
                        <option value="oldest">Más antiguos</option>
                        <option value="name">Nombre (A–Z)</option>
                    </flux:select>
                </div>

            </div>
        </div>

        {{-- Cards de periodos - Diseño minimalista mejorado --}}
        @if($periods->count())
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5 rounded-xl p-4"
            >

                @foreach($periods as $period)
                    <a href="{{ route('periods.detail', $period->id) }}" class="block group">
                        <div class="relative rounded-2xl overflow-hidden transition-all duration-300 hover:scale-[1.02]"
                            style="background-color: var(--student-document-bg-card); 
                                    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);">
                            
                            {{-- Badge de estado flotante --}}
                            <div class="absolute top-4 right-4 z-10">
                                <span
                                    class="flex items-center justify-center px-3 py-1.5 rounded-full backdrop-blur-sm">
                                    <span
                                        class="w-2 h-2 rounded-full"
                                        style="background-color: {{ $period->is_active
                                            ? 'var(--student-document-text-content-status-approved-icon)'
                                            : 'var(--student-document-text-secondary)' }};">
                                    </span>
                                </span>
                            </div>

                            {{-- Header simplificado --}}
                            <div class="p-6 pb-4" 
                                style="background: {{ $period->is_active ? 'linear-gradient(135deg, var(--student-document-bg-card-header-approved) 0%, var(--student-document-bg-card-header-approved-2) 100%)' : 'var(--student-document-bg-card-header)' }};">
                                <h2 class="text-xl font-bold mb-3 pr-20 leading-tight" style="color: var(--student-document-text-primary);">
                                    {{ $period->name }}
                                </h2>
                                
                                <div class="flex items-center gap-2 text-sm" style="color: var(--student-document-text-secondary);">
                                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    <span class="font-medium">{{ $period->startFormatted }} — {{ $period->endFormatted }}</span>
                                </div>
                            </div>

                            {{-- Estadísticas principales --}}
                            <div class="px-6 py-5" style="background-color: var(--student-document-bg-card);">
                                {{-- Estadísticas / Estado vacío (misma altura real) --}}
                                    <div class="min-h-[104px] flex items-center justify-center mb-5">

                                        @if($period->hasStudents)
                                            <div class="flex items-center justify-between gap-3 w-full">
                                                {{-- Aprobados --}}
                                                <div class="flex-1 text-center">
                                                    <div class="text-3xl font-bold mb-1"
                                                        style="color: var(--student-document-text-content-status-approved-icon);">
                                                        {{ $period->approved_students_count }}
                                                    </div>
                                                    <div class="text-xs font-semibold uppercase tracking-wide"
                                                        style="color: var(--student-document-text-secondary);">
                                                        Aprobados
                                                    </div>
                                                </div>

                                                <div class="w-px h-12 rounded-full"
                                                    style="background-color: var(--student-document-border-content);"></div>

                                                {{-- Pendientes --}}
                                                <div class="flex-1 text-center">
                                                    <div class="text-3xl font-bold mb-1"
                                                        style="color: var(--student-document-calendar-pending);">
                                                        {{ $period->pending_students_count }}
                                                    </div>
                                                    <div class="text-xs font-semibold uppercase tracking-wide"
                                                        style="color: var(--student-document-text-secondary);">
                                                        Pendientes
                                                    </div>
                                                </div>

                                                <div class="w-px h-12 rounded-full"
                                                    style="background-color: var(--student-document-border-content);"></div>

                                                {{-- Rechazados --}}
                                                <div class="flex-1 text-center">
                                                    <div class="text-3xl font-bold mb-1"
                                                        style="color: var(--student-document-text-content-status-rejected-icon);">
                                                        {{ $period->rejected_students_count }}
                                                    </div>
                                                    <div class="text-xs font-semibold uppercase tracking-wide"
                                                        style="color: var(--student-document-text-secondary);">
                                                        Rechazados
                                                    </div>
                                                </div>
                                            </div>
                                        @else
                                            <div class="text-center">
                                                <div class="inline-flex items-center justify-center w-12 h-12 rounded-full mb-2"
                                                    style="background-color: var(--student-document-bg-content);">
                                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                                        style="color: var(--student-document-text-secondary);">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                                                    </svg>
                                                </div>
                                                <p class="text-sm font-medium"
                                                style="color: var(--student-document-text-secondary);">
                                                    Sin estudiantes
                                                </p>
                                            </div>
                                        @endif

                                    </div>

                                {{-- Semestres tags --}}
                                @if($period->semesters->count())
                                    <div class="flex flex-wrap gap-1.5 mb-4">
                                        @foreach($period->semesters as $semester)
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-semibold"
                                                style="background-color: var(--student-document-bg-content-status-normal); 
                                                        color: var(--student-document-text-content-status-normal-icon);">
                                                {{ $semester->name }}
                                            </span>
                                        @endforeach
                                    </div>
                                @endif


                                {{-- Métricas compactas --}}
                                <div class="flex items-center justify-between text-sm pt-4" 
                                    style="border-top: 1px solid var(--student-document-border-content);">
                                    <div class="flex items-center gap-1.5">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                            style="color: var(--student-document-text-secondary);">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                                        </svg>
                                        <span class="font-bold" style="color: var(--student-document-text-primary);">{{ $period->students_count }}</span>
                                        <span style="color: var(--student-document-text-secondary);">estudiantes</span>
                                    </div>
                                    <div class="flex items-center gap-1.5">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                            style="color: var(--student-document-text-secondary);">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                        </svg>
                                        <span class="font-bold" style="color: var(--student-document-text-primary);">{{ $period->files_count }}</span>
                                        <span style="color: var(--student-document-text-secondary);">docs</span>
                                    </div>
                                </div>
                            </div>

                            {{-- Acciones footer --}}
                            <div class="px-6 py-3 flex items-center justify-end gap-2" 
                                style="background-color: var(--student-document-bg-content); 
                                        border-top: 1px solid var(--student-document-border-content);">
                                <div class="flex gap-1.5">
                                    <button wire:click.prevent="editPeriod({{ $period->id }})"
                                        class="inline-flex items-center justify-center w-9 h-9 rounded-lg transition-all hover:scale-105"
                                        style="color: var(--student-document-text-primary); 
                                            background-color: var(--student-document-bg-card); 
                                            border: 1px solid var(--student-document-border-content);"
                                        title="Editar periodo">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </button>

                                    @if(!$period->is_active)
                                        <button 
                                            wire:click.prevent="confirmDelete({{ $period->id }})"
                                            class="inline-flex items-center justify-center w-9 h-9 rounded-lg transition-all hover:scale-105"
                                            style="color: var(--student-document-text-content-status-rejected-icon); 
                                                background-color: var(--student-document-bg-content-status-rejected); 
                                                border: 1px solid var(--status-border-rejected);"
                                            title="Eliminar periodo">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    @endif
                                </div>

                            </div>
                        </div>
                    </a>
                @endforeach
            </div>

            {{-- Paginación --}}
            <div class="mt-6">
                {{ $periods->links() }}
            </div>

        @else
            {{-- Estado vacío minimalista --}}
            <div class="text-center py-20 rounded-2xl border border-dashed"
                style="background-color: var(--student-document-bg-card); 
                        border-color: var(--student-document-border-content);">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl mb-4"
                    style="background-color: var(--student-document-bg-content);">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        style="color: var(--student-document-text-secondary);">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <h3 class="text-lg font-bold mb-2" style="color: var(--student-document-text-primary);">
                    {{ $search ? 'Sin resultados' : 'No hay periodos académicos' }}
                </h3>
                <p class="mb-6 max-w-sm mx-auto text-sm" style="color: var(--student-document-text-secondary);">
                    {{ $search ? 'No se encontraron periodos con esos términos.' : 'Crea el primer periodo para comenzar.' }}
                </p>
                @if(!$search)
                    <flux:button variant="primary" wire:click="createPeriod" icon="plus">
                        Crear Periodo
                    </flux:button>
                @endif
            </div>
        @endif
    </div>

    {{-- Modal creación/edición --}}
    {{-- ================== MODAL CREACIÓN/EDICIÓN PERIODO ACADÉMICO ================== --}}
    <flux:modal 
        wire:model="isOpen" 
        :dismissible="false"
        class="w-[95vw] sm:w-[85vw] md:w-[650px] lg:w-[700px] max-w-[95vw]">
        <div class="flex flex-col max-h-[85vh]">

            {{-- Header --}}
            <div 
                class="relative px-6 py-5 flex items-center gap-4 overflow-hidden flex-shrink-0 rounded-t-xl border-b"
                style="
                    background: linear-gradient(
                        135deg,
                        var(--student-document-bg-card-header) 0%,
                        var(--student-document-bg-card-header-2) 100%
                    );
                    border-bottom: 1px solid var(--student-document-border-content);
                ">
                {{-- Icono --}}
                <div 
                    class="w-12 h-12 rounded-xl flex items-center justify-center flex-shrink-0 shadow-lg"
                    style="background: linear-gradient(135deg, var(--color-hero-accent-primary), var(--color-hero-accent-secondary));">
                    @if($periodId)
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                    @else
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                    @endif
                </div>

                {{-- Título y descripción --}}
                <div class="flex-1 min-w-0">
                    <h3 class="text-lg font-bold" style="color: var(--student-document-text-primary);">
                        {{ $periodId ? 'Editar Periodo Académico' : 'Nuevo Periodo Académico' }}
                    </h3>
                    <p class="text-sm truncate" style="color: var(--student-document-text-secondary);">
                        {{ $periodId ? 'Modifique los datos del periodo seleccionado' : 'Complete la información requerida' }}
                    </p>
                </div>
            </div>

            {{-- Contenido --}}
            <div class="flex-1 overflow-y-auto p-6 space-y-5" style="background-color: var(--student-document-bg-card);">

                {{-- Nombre del Periodo --}}
                <div 
                    class="p-4 rounded-xl border"
                    style="
                        background-color: var(--student-document-bg-content);
                        border-color: var(--student-document-border-content);
                    ">
                    <label class="block text-sm font-semibold mb-2" style="color: var(--student-document-text-primary);">
                        Nombre del Periodo 
                        <span style="color: var(--student-document-text-content-status-rejected-icon);">*</span>
                    </label>
                    <flux:input 
                        wire:model.defer="name" 
                        type="text" 
                        placeholder="Ejemplo: Enero-Junio 2025"
                        class="w-full"
                        style="
                            background-color: var(--student-document-bg-card); 
                            border-color: var(--student-document-border-content); 
                            color: var(--modal-text-primary);
                        "/>
                    <flux:error name="name" />
                </div>

                {{-- Fechas --}}
                <div 
                    class="p-4 rounded-xl border"
                    style="
                        background-color: var(--student-document-bg-content);
                        border-color: var(--student-document-border-content);
                    ">
                    <div class="flex items-center gap-2 mb-4">
                        <div class="p-2 rounded-lg" style="background-color: var(--student-document-bg-card);">
                            <svg class="w-5 h-5" style="color: var(--student-document-progress-indicador);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <h4 class="text-base font-semibold" style="color: var(--student-document-text-primary);">
                            Periodo de Vigencia
                        </h4>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold mb-1.5" style="color: var(--student-document-text-secondary);">
                                Fecha de Inicio <span style="color: var(--student-document-text-content-status-rejected-icon);">*</span>
                            </label>
                            <flux:input 
                                wire:model.defer="start_date" 
                                type="date"
                                class="w-full"
                                style="
                                    background-color: var(--student-document-bg-card); 
                                    border-color: var(--student-document-border-content); 
                                    color: var(--modal-text-primary);
                                "/>
                            <flux:error name="start_date" />
                        </div>

                        <div>
                            <label class="block text-xs font-semibold mb-1.5" style="color: var(--student-document-text-secondary);">
                                Fecha de Término <span style="color: var(--student-document-text-content-status-rejected-icon);">*</span>
                            </label>
                            <flux:input 
                                wire:model.defer="end_date" 
                                type="date"
                                class="w-full"
                                style="
                                    background-color: var(--student-document-bg-card); 
                                    border-color: var(--student-document-border-content); 
                                    color: var(--modal-text-primary);
                                "/>
                            <flux:error name="end_date" />
                        </div>
                    </div>
                </div>

                {{-- Semestres Incluidos --}}
                <div 
                    class="p-4 rounded-xl border"
                    style="
                        background-color: var(--student-document-bg-content);
                        border-color: var(--student-document-border-content);
                    ">
                    <div class="flex items-center gap-2 mb-4">
                        <div class="p-2 rounded-lg" style="background-color: var(--student-document-bg-card);">
                            <svg class="w-5 h-5" style="color: var(--student-document-progress-indicador);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                            </svg>
                        </div>
                        <h4 class="text-base font-semibold" style="color: var(--student-document-text-primary);">
                            Semestres Incluidos <span style="color: var(--student-document-text-content-status-rejected-icon);">*</span>
                        </h4>
                    </div>

                    <div 
                        class="space-y-2 max-h-52 overflow-y-auto p-4 rounded-lg" 
                        style="
                            border: 1px solid var(--student-document-border-content); 
                            background-color: var(--student-document-bg-card);
                        ">
                        @forelse($semesters as $semester)
                            <label class="flex items-center gap-3 p-2 rounded-lg cursor-pointer transition-all hover:bg-opacity-50"
                                style="background-color: transparent;"
                                onmouseover="this.style.backgroundColor='var(--student-document-bg-content)'"
                                onmouseout="this.style.backgroundColor='transparent'">
                                <flux:checkbox 
                                    wire:model.defer="selectedSemesters" 
                                    value="{{ $semester->id }}"
                                />
                                <span class="text-sm font-medium" style="color: var(--modal-text-primary);">
                                    {{ $semester->name }}
                                </span>
                            </label>
                        @empty
                            <div class="text-center py-8">
                                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full mb-4"
                                    style="background-color: var(--student-document-bg-content);">
                                    <svg class="w-8 h-8" style="color: var(--student-document-text-secondary);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                                    </svg>
                                </div>
                                <p class="text-sm font-medium mb-1" style="color: var(--modal-text-primary);">
                                    No hay semestres disponibles
                                </p>
                                <p class="text-xs" style="color: var(--student-document-text-secondary);">
                                    Debe crear semestres antes de asignarlos a un periodo
                                </p>
                            </div>
                        @endforelse
                    </div>
                    <flux:error name="selectedSemesters" />
                </div>

                {{-- Estado activo --}}
                <div 
                    class="p-4 rounded-xl border"
                    style="
                        background-color: var(--student-document-bg-content);
                        border-color: var(--student-document-border-content);
                    ">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="p-2 rounded-lg" style="background-color: var(--student-document-bg-card);">
                                <svg class="w-5 h-5" style="color: var(--student-document-progress-indicador);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-semibold" style="color: var(--student-document-text-primary);">
                                    Periodo activo
                                </p>
                                <p class="text-xs" style="color: var(--student-document-text-secondary);">
                                    Este periodo estará disponible para los estudiantes
                                </p>
                            </div>
                        </div>
                        <flux:switch wire:model="is_active" />
                    </div>
                </div>

            </div>

            {{-- Footer con botones --}}
            <div 
                class="px-6 py-4 flex flex-col-reverse sm:flex-row items-center justify-end gap-3 border-t flex-shrink-0 rounded-b-xl"
                style="
                    background-color: var(--student-document-bg-card);
                    border-color: var(--student-document-border-content);
                ">
                {{-- Botón Cancelar --}}
                <button
                    wire:click="$set('isOpen', false)"
                    class="w-full sm:w-auto px-5 py-2.5 rounded-lg font-medium transition-all duration-200"
                    style="
                        background-color: var(--modal-btn-close);
                                    color: var(--modal-btn-close-text);
                    "
                    onmouseover="this.style.transform='translateY(-2px)'; this.style.filter='brightness(1.05)';"
                                onmouseout="this.style.transform='translateY(0)'; this.style.filter='brightness(1)';">
                    Cancelar
                </button>

                {{-- Botón Guardar/Crear --}}
                <button
                    wire:click="save"
                    class="w-full sm:w-auto px-6 py-2.5 rounded-lg font-semibold transition-all duration-200 shadow-lg text-white"
                    style="background: linear-gradient(135deg, var(--color-hero-accent-primary) 0%, var(--color-hero-accent-secondary) 100%);"
                    onmouseover="this.style.transform='translateY(-2px)'; this.style.filter='brightness(1.05)';"
                                onmouseout="this.style.transform='translateY(0)'; this.style.filter='brightness(1)';">
                    <span class="flex items-center justify-center gap-2">
                        
                        {{ $periodId ? 'Guardar Cambios' : 'Crear' }}
                    </span>
                </button>
            </div>

        </div>
    </flux:modal>

    {{-- ================== MODAL CONFIRMACIÓN ELIMINACIÓN PERIODO ACADÉMICO ================== --}}
    <flux:modal 
        wire:model="isDeleteModalOpen" 
        :dismissible="false"
        class="w-[95vw] sm:w-[450px] max-w-[95vw]">
        <div class="flex flex-col">

            

            {{-- Contenido --}}
            <div class="px-6 py-6" style="background-color: var(--student-document-bg-card);">
                <div 
                    class="p-4 rounded-xl border"
                    style="
                        background-color: var(--student-document-bg-content);
                        border-color: var(--student-document-border-content);
                    ">
                    <div class="flex items-start gap-3">
                        <div class="flex-shrink-0 p-2 rounded-lg" style="background-color: var(--student-document-bg-card);">
                            <svg class="w-5 h-5" style="color: var(--student-document-text-content-status-rejected-icon);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-medium mb-1" style="color: var(--student-document-text-primary);">
                                ¿Está seguro de que desea eliminar este periodo académico?
                            </p>
                            <p class="text-xs" style="color: var(--student-document-text-secondary);">
                                Se eliminarán todos los datos asociados y esta acción es irreversible.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Footer con botones --}}
            <div 
                class="px-6 py-4 flex flex-col-reverse sm:flex-row items-center justify-end gap-3 border-t flex-shrink-0 rounded-b-xl"
                style="
                    background-color: var(--student-document-bg-card);
                    border-color: var(--student-document-border-content);
                ">
                {{-- Botón Cancelar --}}
                <button
                    wire:click="$set('isDeleteModalOpen', false)"
                    class="w-full sm:w-auto px-5 py-2.5 rounded-lg font-medium transition-all duration-200"
                    style="
                        background-color: var(--modal-btn-close);
                        color: var(--modal-btn-close-text);
                    "
                    onmouseover="this.style.transform='translateY(-2px)'; this.style.filter='brightness(1.05)';"
                    onmouseout="this.style.transform='translateY(0)'; this.style.filter='brightness(1)';">
                    Cancelar
                </button>

                {{-- Botón Eliminar --}}
                <button
                    wire:click="deletePeriod"
                    class="w-full sm:w-auto px-6 py-2.5 rounded-lg font-semibold transition-all duration-200 shadow-lg text-white"
                    style="background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);"
                    onmouseover="this.style.transform='translateY(-2px)'; this.style.filter='brightness(1.05)';"
                    onmouseout="this.style.transform='translateY(0)'; this.style.filter='brightness(1)';">
                    <span class="flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                        Eliminar
                    </span>
                </button>
            </div>

        </div>
    </flux:modal>


    </div>
