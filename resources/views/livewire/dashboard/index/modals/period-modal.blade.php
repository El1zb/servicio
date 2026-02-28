{{-- =================== MODAL CREAR / EDITAR PERIODO =================== --}}
<flux:modal wire:model="isOpen" :dismissible="false"
    class="w-[95vw] sm:w-[90vw] md:w-[85vw] lg:w-[860px] xl:w-[960px] max-w-[95vw]">

    <div class="flex flex-col" style="height: 80vh; max-height: 88vh;">

        {{-- ── Header ── --}}
        <div class="flex items-center justify-between px-4 sm:px-6 py-3 sm:py-4 flex-shrink-0"
            style="border-bottom: 1px solid var(--period-detail-border); background-color: var(--period-detail-card-bg);">

            <div class="flex items-center gap-3 min-w-0 flex-1">
                <div class="min-w-0 flex-1">
                    <h3 class="text-sm font-semibold leading-tight truncate"
                        style="color: var(--period-detail-text-primary);">
                        {{ $periodId ? 'Editar Periodo Académico' : 'Nuevo Periodo Académico' }}
                    </h3>
                    <p class="text-xs mt-0.5 truncate" style="color: var(--period-detail-text-secondary);">
                        {{ $periodId ? 'Modifique los datos del periodo seleccionado' : 'Complete la información requerida' }}
                    </p>
                </div>
            </div>

            {{-- Badge estado activo --}}
            <div class="flex-shrink-0 ml-3">
                @if($periodId)
                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold border"
                        style="{{ $is_active
                            ? 'background-color: var(--period-detail-status-approved-bg); color: var(--period-detail-status-approved-icon-color); border-color: rgba(16,185,129,0.3);'
                            : 'background-color: var(--period-detail-bg); color: var(--period-detail-text-secondary); border-color: var(--period-detail-border);' }}">
                        {{ $is_active ? 'Activo' : 'Inactivo' }}
                    </span>
                @else
                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold border"
                        style="background-color: var(--period-detail-bg); color: var(--period-detail-text-secondary); border-color: var(--period-detail-border);">
                        Nuevo
                    </span>
                @endif
            </div>
        </div>

        {{-- ── Layout 2 columnas ── --}}
        <div class="grid grid-cols-1 lg:grid-cols-[1fr_320px] xl:grid-cols-[1fr_360px] gap-0 flex-1 overflow-hidden">

            {{-- ── Columna izquierda: Nombre + Fechas + Semestres ── --}}
            <div class="flex flex-col overflow-y-auto"
                style="border-right: 1px solid var(--period-detail-border); background-color: var(--period-detail-bg);">

                <div class="flex flex-col gap-3 p-3 sm:p-4">

                    {{-- Nombre del periodo --}}
                    <div class="p-3 rounded-lg border"
                        style="background-color: var(--period-detail-card-bg); border-color: var(--period-detail-border);">

                        <p class="text-xs font-semibold mb-2 flex items-center gap-1.5"
                            style="color: var(--period-detail-text-primary);">
                            <svg class="w-3.5 h-3.5 flex-shrink-0" style="color: var(--period-detail-accent);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A2 2 0 013 12V7a2 2 0 012-2z"/>
                            </svg>
                            Nombre del Periodo
                            <span style="color: rgba(239,68,68,0.8);">*</span>
                        </p>

                        <input type="text"
                            wire:model.defer="name"
                            placeholder="Ejemplo: Enero-Junio 2025"
                            class="w-full px-3 py-2 rounded-lg border text-xs transition-all focus:outline-none focus:ring-1"
                            style="background-color: var(--period-detail-bg);
                                   border-color: var(--period-detail-border);
                                   color: var(--period-detail-text-primary);
                                   --tw-ring-color: var(--period-detail-accent);">

                        @error('name')
                            <p class="mt-1.5 text-xs flex items-center gap-1.5" style="color: #ee6e6c;">
                                <svg class="w-3.5 h-3.5 flex-shrink-0" viewBox="0 0 20 20" fill="#ee6e6c">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.72-1.36 3.485 0l6.518 11.59c.75 1.335-.213 2.971-1.742 2.971H3.48c-1.529 0-2.492-1.636-1.742-2.971L8.257 3.1zM11 13a1 1 0 10-2 0 1 1 0 002 0zm-1-8a1 1 0 00-.993.883L9 6v4a1 1 0 001.993.117L11 10V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Fechas --}}
                    <div class="p-3 rounded-lg border"
                        style="background-color: var(--period-detail-card-bg); border-color: var(--period-detail-border);">

                        <p class="text-xs font-semibold mb-2 flex items-center gap-1.5"
                            style="color: var(--period-detail-text-primary);">
                            <svg class="w-3.5 h-3.5 flex-shrink-0" style="color: var(--period-detail-accent);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            Periodo de Vigencia
                            <span style="color: rgba(239,68,68,0.8);">*</span>
                        </p>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">

                            {{-- Fecha inicio --}}
                            <div>
                                <p class="text-xs mb-1.5" style="color: var(--period-detail-text-secondary);">Fecha de Inicio</p>
                                <input type="date"
                                    wire:model.defer="start_date"
                                    class="w-full px-3 py-2 rounded-lg border text-xs transition-all focus:outline-none focus:ring-1"
                                    style="background-color: var(--period-detail-bg);
                                           border-color: var(--period-detail-border);
                                           color: var(--period-detail-text-primary);
                                           --tw-ring-color: var(--period-detail-accent);">
                                @error('start_date')
                                    <p class="mt-1.5 text-xs flex items-center gap-1.5" style="color: #ee6e6c;">
                                        <svg class="w-3.5 h-3.5 flex-shrink-0" viewBox="0 0 20 20" fill="#ee6e6c">
                                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.72-1.36 3.485 0l6.518 11.59c.75 1.335-.213 2.971-1.742 2.971H3.48c-1.529 0-2.492-1.636-1.742-2.971L8.257 3.1zM11 13a1 1 0 10-2 0 1 1 0 002 0zm-1-8a1 1 0 00-.993.883L9 6v4a1 1 0 001.993.117L11 10V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                        </svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            {{-- Fecha fin --}}
                            <div>
                                <p class="text-xs mb-1.5" style="color: var(--period-detail-text-secondary);">Fecha de Término</p>
                                <input type="date"
                                    wire:model.defer="end_date"
                                    class="w-full px-3 py-2 rounded-lg border text-xs transition-all focus:outline-none focus:ring-1"
                                    style="background-color: var(--period-detail-bg);
                                           border-color: var(--period-detail-border);
                                           color: var(--period-detail-text-primary);
                                           --tw-ring-color: var(--period-detail-accent);">
                                @error('end_date')
                                    <p class="mt-1.5 text-xs flex items-center gap-1.5" style="color: #ee6e6c;">
                                        <svg class="w-3.5 h-3.5 flex-shrink-0" viewBox="0 0 20 20" fill="#ee6e6c">
                                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.72-1.36 3.485 0l6.518 11.59c.75 1.335-.213 2.971-1.742 2.971H3.48c-1.529 0-2.492-1.636-1.742-2.971L8.257 3.1zM11 13a1 1 0 10-2 0 1 1 0 002 0zm-1-8a1 1 0 00-.993.883L9 6v4a1 1 0 001.993.117L11 10V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                        </svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    {{-- Semestres --}}
                    <div class="p-3 rounded-lg border flex flex-col"
                        style="background-color: var(--period-detail-card-bg); border-color: var(--period-detail-border);">

                        <p class="text-xs font-semibold mb-1 flex items-center gap-1.5"
                            style="color: var(--period-detail-text-primary);">
                            <svg class="w-3.5 h-3.5 flex-shrink-0" style="color: var(--period-detail-accent);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                            </svg>
                            Semestres Incluidos
                            <span style="color: rgba(239,68,68,0.8);">*</span>
                        </p>
                        <p class="text-xs mb-2" style="color: var(--period-detail-text-secondary);">Selecciona los semestres que forman parte de este periodo.</p>

                        <div class="rounded-lg border overflow-y-auto"
                            style="max-height: 220px; border-color: var(--period-detail-border); background-color: var(--period-detail-bg);">

                            @forelse($semesters as $semester)
                                <label class="flex items-center gap-3 px-3 py-2 cursor-pointer transition-all border-b last:border-b-0"
                                    style="border-color: var(--period-detail-border);"
                                    onmouseover="this.style.backgroundColor='var(--period-detail-card-bg)'"
                                    onmouseout="this.style.backgroundColor='transparent'">
                                    <flux:checkbox wire:model.defer="selectedSemesters" value="{{ $semester->id }}"
                                        class="flex-shrink-0"/>
                                    <span class="text-xs font-medium" style="color: var(--period-detail-text-primary);">
                                        {{ $semester->name }}
                                    </span>
                                </label>
                            @empty
                                <div class="flex flex-col items-center justify-center py-8 text-center px-4">
                                    <div class="w-10 h-10 rounded-xl flex items-center justify-center mb-2"
                                        style="background-color: var(--period-detail-card-bg);">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                            style="color: var(--period-detail-text-secondary);">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                                        </svg>
                                    </div>
                                    <p class="text-xs font-medium mb-0.5" style="color: var(--period-detail-text-primary);">No hay semestres disponibles</p>
                                    <p class="text-xs" style="color: var(--period-detail-text-secondary);">Crea semestres antes de asignarlos</p>
                                </div>
                            @endforelse
                        </div>

                        @error('selectedSemesters')
                            <p class="mt-1.5 text-xs flex items-center gap-1.5" style="color: #ee6e6c;">
                                <svg class="w-3.5 h-3.5 flex-shrink-0" viewBox="0 0 20 20" fill="#ee6e6c">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.72-1.36 3.485 0l6.518 11.59c.75 1.335-.213 2.971-1.742 2.971H3.48c-1.529 0-2.492-1.636-1.742-2.971L8.257 3.1zM11 13a1 1 0 10-2 0 1 1 0 002 0zm-1-8a1 1 0 00-.993.883L9 6v4a1 1 0 001.993.117L11 10V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                </div>
            </div>

            {{-- ── Columna derecha: Opciones + Acciones ── --}}
            <div class="flex flex-col overflow-y-auto"
                style="background-color: var(--period-detail-card-bg);">

                <div class="flex flex-col gap-3 p-3 sm:p-4 flex-1">

                    {{-- Toggle periodo activo --}}
                    <div class="p-3 rounded-lg border"
                        style="background-color: var(--period-detail-bg); border-color: var(--period-detail-border);">

                        <p class="text-xs font-semibold mb-2 flex items-center gap-1.5"
                            style="color: var(--period-detail-text-primary);">
                            <svg class="w-3.5 h-3.5 flex-shrink-0" style="color: var(--period-detail-accent);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Estado del Periodo
                        </p>

                        <div class="flex items-center justify-between p-2 rounded-lg border"
                            style="background-color: var(--period-detail-card-bg); border-color: var(--period-detail-border);">
                            <div class="flex-1 min-w-0">
                                <p class="text-xs font-medium" style="color: var(--period-detail-text-primary);">Periodo activo</p>
                                <p class="text-xs" style="color: var(--period-detail-text-secondary);">Visible para los estudiantes</p>
                            </div>
                            <flux:switch wire:model="is_active" class="flex-shrink-0 ml-3"/>
                        </div>
                    </div>

                    {{-- Resumen / Preview de datos --}}
                    @if($name || $start_date || $end_date)
                        <div class="p-3 rounded-lg border"
                            style="background-color: var(--period-detail-bg); border-color: var(--period-detail-border);">

                            <p class="text-xs font-semibold mb-2 flex items-center gap-1.5"
                                style="color: var(--period-detail-text-primary);">
                                <svg class="w-3.5 h-3.5 flex-shrink-0" style="color: var(--period-detail-accent);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                Vista Previa
                            </p>

                            <div class="space-y-1.5">
                                @if($name)
                                    <div class="flex items-start gap-2">
                                        <span class="text-xs flex-shrink-0 mt-0.5" style="color: var(--period-detail-text-secondary);">Nombre:</span>
                                        <span class="text-xs font-medium break-words" style="color: var(--period-detail-text-primary);">{{ $name }}</span>
                                    </div>
                                @endif
                                @if($start_date)
                                    <div class="flex items-center gap-2">
                                        <span class="text-xs flex-shrink-0" style="color: var(--period-detail-text-secondary);">Inicio:</span>
                                        <span class="text-xs font-medium" style="color: var(--period-detail-text-primary);">
                                            {{ \Carbon\Carbon::parse($start_date)->format('d/m/Y') }}
                                        </span>
                                    </div>
                                @endif
                                @if($end_date)
                                    <div class="flex items-center gap-2">
                                        <span class="text-xs flex-shrink-0" style="color: var(--period-detail-text-secondary);">Término:</span>
                                        <span class="text-xs font-medium" style="color: var(--period-detail-text-primary);">
                                            {{ \Carbon\Carbon::parse($end_date)->format('d/m/Y') }}
                                        </span>
                                    </div>
                                @endif
                                @if(!empty($selectedSemesters))
                                    <div class="flex items-center gap-2">
                                        <span class="text-xs flex-shrink-0" style="color: var(--period-detail-text-secondary);">Semestres:</span>
                                        <span class="text-xs font-medium" style="color: var(--period-detail-text-primary);">
                                            {{ count($selectedSemesters) }} seleccionado(s)
                                        </span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif

                    {{-- Botón guardar / crear --}}
                    <div class="flex-1 flex flex-col justify-end">
                        <button wire:click="save" wire:loading.attr="disabled"
                            class="w-full px-4 py-2.5 text-xs font-semibold rounded-lg border border-transparent shadow-sm transition-all duration-200 hover:-translate-y-0.5 hover:shadow-lg disabled:opacity-50 flex items-center justify-center gap-2"
                            style="background-color: var(--index-btn-primary-bg); color: var(--index-btn-primary-text);"
                            onmouseover="this.style.opacity='0.85'"
                            onmouseout="this.style.opacity='1'">
                            <span wire:loading.remove wire:target="save">
                                {{ $periodId ? 'Guardar Cambios' : 'Crear Periodo' }}
                            </span>
                            <span wire:loading wire:target="save">Guardando...</span>
                        </button>
                    </div>

                </div>

                {{-- ── Footer col derecha: Cerrar ── --}}
                <div class="flex-shrink-0 px-3 sm:px-4 py-3"
                    style="border-top: 1px solid var(--period-detail-border);">

                    <button wire:click="$set('isOpen', false)"
                        class="w-full px-4 py-2 text-xs font-medium rounded-lg transition-all duration-200 hover:-translate-y-0.5 text-center"
                        style="color: var(--period-detail-text-secondary); background-color: transparent;"
                        onmouseover="this.style.color='var(--period-detail-text-primary)'; this.style.backgroundColor='var(--period-detail-border)'"
                        onmouseout="this.style.color='var(--period-detail-text-secondary)'; this.style.backgroundColor='transparent'">
                        Cancelar
                    </button>

                </div>
            </div>

        </div>

    </div>
</flux:modal>