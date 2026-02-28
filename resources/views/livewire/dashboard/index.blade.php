<div class="space-y-8 min-h-screen p-6">

    {{-- Header --}}
    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-6 rounded-xl shadow-sm p-6"
         style="background-color: var(--index-bg);">
        <x-auth-header
            title="Periodos Académicos"
            description="Administración y seguimiento de periodos académicos."
            :center="false"
        />
        <flux:button variant="primary" wire:click="createPeriod" icon="plus"
            class="group relative inline-flex items-center justify-center px-4 py-2 rounded-[var(--radius-md)]
                   bg-[var(--index-btn-primary-bg)]! text-[var(--index-btn-primary-text)]!
                   shadow-lg shadow-[var(--index-btn-primary-shadow)]
                   hover:bg-[var(--index-btn-primary-hover)]! hover:shadow-xl hover:-translate-y-0.5
                   transition-all duration-300 disabled:opacity-60 disabled:cursor-not-allowed gap-2">
            Nuevo Periodo
        </flux:button>
    </div>

    {{-- Stats --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5">

        @php
            $statCards = [
                ['label' => 'Total Periodos',        'value' => $stats['total_periods'],    'bg' => 'var(--index-status-normal-bg)',    'icon_color' => 'var(--index-status-normal-icon)',
                 'icon' => 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z'],
                ['label' => 'Periodos Activos',      'value' => $stats['active_periods'],   'bg' => 'var(--index-status-approved-bg)', 'icon_color' => 'var(--index-status-approved-icon)',
                 'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
                ['label' => 'Total Estudiantes',     'value' => $stats['total_students'],   'bg' => 'var(--index-icon-bg)',            'icon_color' => 'var(--index-icon-text)',
                 'icon' => 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z'],
                ['label' => 'Aprobaciones Pendientes','value' => $stats['pending_approvals'],'bg' => 'rgba(232,210,50,0.1)',          'icon_color' => 'var(--index-status-pending)',
                 'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z'],
            ];
        @endphp

        @foreach($statCards as $card)
            <div class="rounded-xl p-6 shadow-lg" style="background-color: var(--index-bg);">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 rounded-lg flex items-center justify-center"
                         style="background-color: {{ $card['bg'] }};">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                             style="color: {{ $card['icon_color'] }};">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $card['icon'] }}"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs font-medium uppercase tracking-wide" style="color: var(--index-text-secondary);">
                            {{ $card['label'] }}
                        </p>
                        <p class="text-2xl font-bold mt-0.5" style="color: var(--index-text-primary);">
                            {{ $card['value'] }}
                        </p>
                    </div>
                </div>
            </div>
        @endforeach

    </div>

    {{-- Main Content --}}
    <div class="rounded-xl p-5 shadow-lg" style="background-color: var(--index-bg);">

        {{-- Filters --}}
        <div class="rounded-xl p-5">
            <div class="grid grid-cols-1 md:grid-cols-12 gap-3 items-center">

                <div class="md:col-span-6 lg:col-span-7 relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                             style="color: var(--index-text-primary);">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                    <input type="text" placeholder="Buscar por nombre..." wire:model.live="search"
                           class="w-full pl-10 pr-4 py-2.5 rounded-lg text-sm transition-all"
                           style="background-color: var(--index-card-bg); color: var(--index-text-primary); border: 1px solid var(--index-border);"/>
                </div>

                <div class="md:col-span-3 lg:col-span-2">
                    <flux:select wire:model.live="statusFilter"
                                 class="w-full px-4 py-2.5 rounded-lg text-sm font-medium transition-all"
                                 style="background-color: var(--index-card-bg); color: var(--index-text-primary); border: 1px solid var(--index-border);">
                        <option value="all">Todos los estados</option>
                        <option value="active">Solo activos</option>
                        <option value="inactive">Solo inactivos</option>
                    </flux:select>
                </div>

                <div class="md:col-span-3 lg:col-span-3">
                    <flux:select wire:model.live="sortBy"
                                 class="w-full px-4 py-2.5 rounded-lg text-sm font-medium transition-all"
                                 style="background-color: var(--index-card-bg); color: var(--index-text-primary); border: 1px solid var(--index-border);">
                        <option value="recent">Más recientes</option>
                        <option value="oldest">Más antiguos</option>
                        <option value="name">Nombre (A–Z)</option>
                    </flux:select>
                </div>

            </div>
        </div>

        @if($periods->count())

            {{-- Period Cards --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 p-4">
                @foreach($periods as $period)
                    <a href="{{ route('periods.detail', $period->id) }}" class="block h-full">
                        <div style="background-color: var(--index-card-bg); border: 1px solid var(--index-border); border-radius: 14px; overflow: hidden; transition: border-color 0.18s ease, box-shadow 0.18s ease; display: flex; flex-direction: column; height: 100%;"
                             onmouseover="this.style.borderColor='rgba(255,255,255,0.1)'; this.style.boxShadow='0 4px 24px rgba(0,0,0,0.25)'"
                             onmouseout="this.style.borderColor='var(--index-border)'; this.style.boxShadow='none'">

                            {{-- Card Body --}}
                            <div style="padding: 18px 18px 14px; flex: 1; display: flex; flex-direction: column;">

                                {{-- Status Badge --}}
                                <div style="margin-bottom: 10px;">
                                    <span style="display: inline-flex; align-items: center; gap: 5px; font-size: 9.5px; font-weight: 600; letter-spacing: 0.1em; text-transform: uppercase; color: {{ $period->is_active ? 'var(--index-status-approved-icon)' : 'var(--index-text-secondary)' }};">
                                        <span style="width: 6px; height: 6px; border-radius: 50%; display: inline-block; flex-shrink: 0; background-color: {{ $period->is_active ? 'var(--index-status-approved-icon)' : 'var(--index-text-secondary)' }}; {{ $period->is_active ? 'box-shadow: 0 0 6px var(--index-status-approved-icon);' : 'opacity:0.4;' }}"></span>
                                        {{ $period->is_active ? 'Activo' : 'Inactivo' }}
                                    </span>
                                </div>

                                <h2 style="font-size: 20px; font-weight: 500; color: var(--index-text-primary); letter-spacing: -0.03em; line-height: 1.15; margin-bottom: 5px;">
                                    {{ $period->name }}
                                </h2>

                                <p style="font-size: 11px; font-weight: 300; color: var(--index-text-secondary); margin-bottom: 16px;">
                                    {{ $period->startFormatted }} — {{ $period->endFormatted }}
                                </p>

                                {{-- Student Stats --}}
                                <div style="margin-bottom: 14px; display: flex; align-items: center;">
                                    @if($period->hasStudents)
                                        <div style="display: flex; gap: 5px; width: 100%; align-items: center;">

                                            @php
                                                $statCols = [
                                                    ['value' => $period->approved_students_count, 'label' => 'Aprobados',  'color' => 'var(--index-status-approved-icon)', 'size' => '20px', 'flex' => '1',   'transform' => ''],
                                                    ['value' => $period->pending_students_count,  'label' => 'Pendientes', 'color' => 'var(--index-status-pending)',       'size' => '30px', 'flex' => '1.4', 'transform' => 'translateY(-6px)'],
                                                    ['value' => $period->rejected_students_count, 'label' => 'Rechazados', 'color' => 'var(--index-status-rejected-icon)', 'size' => '20px', 'flex' => '1',   'transform' => ''],
                                                ];
                                            @endphp

                                            @foreach($statCols as $col)
                                                <div style="flex: {{ $col['flex'] }}; min-width: 0; padding: 8px 6px; display: flex; flex-direction: column; justify-content: center; align-items: center; {{ $col['transform'] ? 'transform: ' . $col['transform'] . ';' : '' }}">
                                                    <div style="font-size: {{ $col['size'] }}; font-weight: 700; color: var(--index-text-primary); letter-spacing: -0.04em; line-height: 1; margin-bottom: 4px;">
                                                        {{ $col['value'] }}
                                                    </div>
                                                    <div style="font-size: 7.5px; font-weight: 600; color: {{ $col['color'] }}; text-transform: uppercase; letter-spacing: 0.06em; white-space: nowrap; text-align: center;">
                                                        {{ $col['label'] }}
                                                    </div>
                                                </div>
                                            @endforeach

                                        </div>
                                    @else
                                        <div style="width: 100%; display: flex; align-items: center; height: 68px;">
                                            <p style="font-size: 11.5px; font-weight: 300; color: var(--index-text-secondary); opacity: 0.45;">
                                                Sin estudiantes registrados
                                            </p>
                                        </div>
                                    @endif
                                </div>

                                {{-- Semesters --}}
                                <div style="margin-top: auto;">
                                    @if($period->semesters->count())
                                        <div style="display: flex; flex-wrap: wrap; gap: 4px;">
                                            @foreach($period->semesters as $semester)
                                                <span style="font-size: 9.5px; font-weight: 400; padding: 2px 8px; border-radius: 4px; color: var(--index-text-secondary); border: 1px solid var(--index-border); letter-spacing: 0.02em;">
                                                    {{ $semester->name }}
                                                </span>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>

                            </div>

                            {{-- Card Footer --}}
                            <div style="padding: 10px 18px; border-top: 1px solid var(--index-border); background-color: var(--index-content-bg); display: flex; align-items: center; justify-content: space-between; flex-shrink: 0;">

                                <div style="display: flex; align-items: center; gap: 5px;">
                                    <span style="font-size: 11px; color: var(--index-text-secondary); font-weight: 300;">
                                        <span style="color: var(--index-text-primary); font-weight: 500;">{{ $period->students_count }}</span> estudiantes
                                    </span>
                                    <span style="width: 3px; height: 3px; border-radius: 50%; background: var(--index-border); display: inline-block; flex-shrink: 0;"></span>
                                    <span style="font-size: 11px; color: var(--index-text-secondary); font-weight: 300;">
                                        <span style="color: var(--index-text-primary); font-weight: 500;">{{ $period->files_count }}</span> docs
                                    </span>
                                </div>

                                <div style="display: flex; gap: 3px;">
                                    <button wire:click.prevent="editPeriod({{ $period->id }})" title="Editar periodo"
                                            style="width: 28px; height: 28px; display: flex; align-items: center; justify-content: center; border-radius: 6px; border: 1px solid transparent; background: transparent; color: var(--index-text-secondary); cursor: pointer; transition: color 0.15s, border-color 0.15s;"
                                            onmouseover="this.style.color='var(--index-text-primary)'; this.style.borderColor='var(--index-border)'"
                                            onmouseout="this.style.color='var(--index-text-secondary)'; this.style.borderColor='transparent'">
                                        <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </button>

                                    @if(!$period->is_active)
                                        <button wire:click.prevent="confirmDelete({{ $period->id }})" title="Eliminar periodo"
                                                style="width: 28px; height: 28px; display: flex; align-items: center; justify-content: center; border-radius: 6px; border: 1px solid transparent; background: transparent; color: var(--index-text-secondary); cursor: pointer; transition: color 0.15s, border-color 0.15s;"
                                                onmouseover="this.style.color='var(--index-status-rejected-icon)'; this.style.borderColor='var(--index-status-rejected-border)'"
                                                onmouseout="this.style.color='var(--index-text-secondary)'; this.style.borderColor='transparent'">
                                            <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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

            <div class="mt-6">
                {{ $periods->links() }}
            </div>

        @else

            {{-- Empty State --}}
            <div class="text-center py-20 rounded-2xl border border-dashed"
                 style="background-color: var(--index-card-bg); border-color: var(--index-border);">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl mb-4"
                     style="background-color: var(--index-content-bg);">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                         style="color: var(--index-text-secondary);">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <h3 class="text-lg font-bold mb-2" style="color: var(--index-text-primary);">
                    {{ $search ? 'Sin resultados' : 'No hay periodos académicos' }}
                </h3>
                <p class="mb-6 max-w-sm mx-auto text-sm" style="color: var(--index-text-secondary);">
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

    {{-- Modal: Create / Edit --}}
    <flux:modal wire:model="isOpen" :dismissible="false"
                class="w-[95vw] sm:w-[85vw] md:w-[650px] lg:w-[700px] max-w-[95vw]">
        <div class="flex flex-col max-h-[85vh]">

            <div class="relative px-6 py-5 flex items-center gap-4 overflow-hidden flex-shrink-0 rounded-t-xl border-b"
                 style="border-bottom: 1px solid var(--index-border);">
                
                <div class="flex-1 min-w-0">
                    <h3 class="text-lg font-bold" style="color: var(--index-text-primary);">
                        {{ $periodId ? 'Editar Periodo Académico' : 'Nuevo Periodo Académico' }}
                    </h3>
                    <p class="text-sm truncate" style="color: var(--index-text-secondary);">
                        {{ $periodId ? 'Modifique los datos del periodo seleccionado' : 'Complete la información requerida' }}
                    </p>
                </div>
            </div>

            <div class="flex-1 overflow-y-auto p-6 space-y-5">

                <div class="p-4 rounded-xl border" style="background-color: var(--index-content-bg); border-color: var(--index-border);">
                    <label class="block text-sm font-semibold mb-2" style="color: var(--index-text-primary);">
                        Nombre del Periodo <span style="color: var(--index-status-rejected-icon);">*</span>
                    </label>
                    <flux:input wire:model.defer="name" type="text" placeholder="Ejemplo: Enero-Junio 2025" class="w-full"
                                style="background-color: var(--index-card-bg); border-color: var(--index-border); color: var(--index-modal-text-primary);"/>
                    <flux:error name="name"/>
                </div>

                <div class="p-4 rounded-xl border" style="background-color: var(--index-content-bg); border-color: var(--index-border);">
                    <div class="flex items-center gap-2 mb-4">
                        <div class="p-2 rounded-lg" style="background-color: var(--index-card-bg);">
                            <svg class="w-5 h-5" style="color: var(--index-accent);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <h4 class="text-base font-semibold" style="color: var(--index-text-primary);">Periodo de Vigencia</h4>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach([['field' => 'start_date', 'label' => 'Fecha de Inicio'], ['field' => 'end_date', 'label' => 'Fecha de Término']] as $dateField)
                            <div>
                                <label class="block text-xs font-semibold mb-1.5" style="color: var(--index-text-secondary);">
                                    {{ $dateField['label'] }} <span style="color: var(--index-status-rejected-icon);">*</span>
                                </label>
                                <flux:input wire:model.defer="{{ $dateField['field'] }}" type="date" class="w-full"
                                            style="background-color: var(--index-card-bg); border-color: var(--index-border); color: var(--index-modal-text-primary);"/>
                                <flux:error name="{{ $dateField['field'] }}"/>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="p-4 rounded-xl border" style="background-color: var(--index-content-bg); border-color: var(--index-border);">
                    <div class="flex items-center gap-2 mb-4">
                        <div class="p-2 rounded-lg" style="background-color: var(--index-card-bg);">
                            <svg class="w-5 h-5" style="color: var(--index-accent);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                            </svg>
                        </div>
                        <h4 class="text-base font-semibold" style="color: var(--index-text-primary);">
                            Semestres Incluidos <span style="color: var(--index-status-rejected-icon);">*</span>
                        </h4>
                    </div>
                    <div class="space-y-2 max-h-52 overflow-y-auto p-4 rounded-lg"
                         style="border: 1px solid var(--index-border); background-color: var(--index-card-bg);">
                        @forelse($semesters as $semester)
                            <label class="flex items-center gap-3 p-2 rounded-lg cursor-pointer transition-all"
                                   onmouseover="this.style.backgroundColor='var(--index-content-bg)'"
                                   onmouseout="this.style.backgroundColor='transparent'">
                                <flux:checkbox wire:model.defer="selectedSemesters" value="{{ $semester->id }}"/>
                                <span class="text-sm font-medium" style="color: var(--index-modal-text-primary);">{{ $semester->name }}</span>
                            </label>
                        @empty
                            <div class="text-center py-8">
                                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full mb-4"
                                     style="background-color: var(--index-content-bg);">
                                    <svg class="w-8 h-8" style="color: var(--index-text-secondary);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                                    </svg>
                                </div>
                                <p class="text-sm font-medium mb-1" style="color: var(--index-modal-text-primary);">No hay semestres disponibles</p>
                                <p class="text-xs" style="color: var(--index-text-secondary);">Debe crear semestres antes de asignarlos a un periodo</p>
                            </div>
                        @endforelse
                    </div>
                    <flux:error name="selectedSemesters"/>
                </div>

                <div class="p-4 rounded-xl border" style="background-color: var(--index-content-bg); border-color: var(--index-border);">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="p-2 rounded-lg" style="background-color: var(--index-card-bg);">
                                <svg class="w-5 h-5" style="color: var(--index-accent);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-semibold" style="color: var(--index-text-primary);">Periodo activo</p>
                                <p class="text-xs" style="color: var(--index-text-secondary);">Este periodo estará disponible para los estudiantes</p>
                            </div>
                        </div>
                        <flux:switch wire:model="is_active"/>
                    </div>
                </div>

            </div>

            <div class="px-6 py-4 flex flex-col-reverse sm:flex-row items-center justify-end gap-2 border-t flex-shrink-0"
                style="border-color: var(--index-border); background-color: var(--index-card-bg);">

                <button wire:click="$set('isOpen', false)"
                        class="w-full sm:w-auto px-4 py-2 rounded-lg text-sm transition-all duration-300 hover:-translate-y-0.5"
                        style="color: var(--index-text-secondary);"
                        onmouseover="this.style.color='var(--index-text-primary)'; this.style.backgroundColor='var(--index-border)'"
                        onmouseout="this.style.color='var(--index-text-secondary)'; this.style.backgroundColor='transparent'">
                    Cancelar
                </button>

                <button wire:click="save"
                        class="w-full sm:w-auto px-4 py-2 rounded-lg text-sm font-medium shadow-lg
                            transition-all duration-300 hover:-translate-y-0.5 hover:shadow-xl
                            disabled:opacity-60 disabled:cursor-not-allowed"
                        style="background-color: var(--index-btn-primary-bg); color: var(--index-btn-primary-text);"
                        onmouseover="this.style.opacity='0.85'"
                        onmouseout="this.style.opacity='1'">
                    {{ $periodId ? 'Guardar' : 'Crear' }}
                </button>

            </div>

        </div>
    </flux:modal>

    {{-- Modal: Delete Confirm --}}
    <flux:modal wire:model="isDeleteModalOpen" :dismissible="false"
                class="w-[95vw] sm:w-[380px] max-w-[95vw]">
        <div class="flex flex-col">

            <div class="px-6 py-5">
                <p class="text-sm font-semibold mb-1" style="color: var(--index-text-secondary);">
                    ¿Seguro que deseas eliminar este periodo?
                </p>
                <p class="text-xs" style="color: var(--index-text-primary);">
                    Esta acción es irreversible.
                </p>
            </div>

            <div class="px-6 py-4 flex items-center justify-end gap-2 border-t"
                style="border-color: var(--index-border); ">

                <button wire:click="$set('isDeleteModalOpen', false)"
                        class="px-4 py-2 rounded-lg text-sm transition-all duration-300 hover:-translate-y-0.5"
                        style="color: var(--index-text-secondary);"
                        onmouseover="this.style.color='var(--index-text-primary)'; this.style.backgroundColor='var(--index-border)'"
                        onmouseout="this.style.color='var(--index-text-secondary)'; this.style.backgroundColor='transparent'">
                    Cancelar
                </button>

                <button wire:click="deletePeriod"
                        class="px-4 py-2 rounded-lg text-sm font-medium shadow-lg text-white
                            transition-all duration-300 hover:-translate-y-0.5 hover:shadow-xl"
                        style="background-color: var(--index-btn-danger-bg);"
                        onmouseover="this.style.opacity='0.85'"
                        onmouseout="this.style.opacity='1'">
                    Eliminar
                </button>

            </div>

        </div>
    </flux:modal>

</div>