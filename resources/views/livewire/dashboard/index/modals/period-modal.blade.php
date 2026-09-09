{{-- =================== MODAL CREAR / EDITAR PERIODO =================== --}}
<flux:modal
    wire:model="isOpen"
    :dismissible="false"
    :closable="false"
    class="w-[95vw] sm:w-[90vw] lg:w-[720px] max-w-[95vw]"
    style="border-color: var(--color-border);">

    <div class="flex flex-col" style="max-height: 90vh;">

        {{-- ── Header ── --}}
        <div class="flex items-center justify-between gap-4 px-6 py-5 flex-shrink-0"
            style="border-bottom: 1px solid var(--color-border);">
            <div class="min-w-0">
                <h2 class="text-lg font-semibold leading-tight" style="color: var(--color-primary-2);">
                    {{ $periodId ? 'Editar Periodo' : 'Nuevo Periodo' }}
                </h2>
                <p class="text-sm mt-0.5" style="color: var(--color-secondary);">
                    {{ $periodId ? 'Actualiza la información del periodo' : 'Se le asignará este periodo a los estudiantes' }}
                </p>
            </div>
        </div>

        {{-- ── Body ── --}}
        <div class="flex-1 overflow-y-auto px-6 py-5" style="background-color: var(--color-modal-bg);">
            <div class="space-y-5">

                {{-- Nombre --}}
                <div class="app-field">
                    <label class="app-field-label">Nombre del periodo <span style="color: #DC2626;">*</span></label>
                    <input type="text" wire:model.live.debounce.500ms="name" placeholder="Ej: Enero-Junio 2027" class="app-input w-full">
                    @error('name') <p class="app-field-error">{{ $message }}</p> @enderror
                </div>

                {{-- Fechas --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="app-field">
                        <label class="app-field-label">Fecha de inicio <span style="color: #DC2626;">*</span></label>
                        <x-date-picker wire:key="period-start-{{ $formInstance }}" wire-model="start_date" :value="$start_date" placeholder="Selecciona una fecha" />
                        @error('start_date') <p class="app-field-error">{{ $message }}</p> @enderror
                    </div>
                    <div class="app-field">
                        <label class="app-field-label">Fecha de término <span style="color: #DC2626;">*</span></label>
                        <x-date-picker wire:key="period-end-{{ $formInstance }}" wire-model="end_date" :value="$end_date" placeholder="Selecciona una fecha" />
                        @error('end_date') <p class="app-field-error">{{ $message }}</p> @enderror
                    </div>
                </div>

                {{-- Semestres incluidos --}}
                <div class="app-field">
                    <label class="app-field-label">Semestres incluidos <span style="color: #DC2626;">*</span></label>
                    <p class="text-xs mb-2" style="color: var(--color-secondary);">Selecciona los semestres que forman parte de este periodo.</p>

                    @forelse($semesters as $semester)
                        <label class="flex items-center gap-3 py-1.5 cursor-pointer" wire:click="toggleSemester({{ $semester->id }})">
                            <span class="individual-toggle-check {{ in_array($semester->id, $selectedSemesters ?? []) ? 'is-checked' : '' }}">
                                <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3.5" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6L9 17l-5-5"/></svg>
                            </span>
                            <span class="text-sm font-medium" style="color: var(--color-primary-2);">{{ $semester->name }}</span>
                        </label>
                    @empty
                        <p class="text-sm" style="color: var(--color-secondary);">No hay semestres disponibles. Crea semestres antes de asignarlos.</p>
                    @endforelse

                    @error('selectedSemesters') <p class="app-field-error">{{ $message }}</p> @enderror
                </div>

                {{-- Periodo activo --}}
                <label class="individual-toggle" wire:click="$toggle('is_active')">
                    <span class="individual-toggle-check {{ $is_active ? 'is-checked' : '' }}">
                        <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3.5" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6L9 17l-5-5"/></svg>
                    </span>
                    <span class="min-w-0">
                        <span class="block text-sm font-medium" style="color: var(--color-primary-2);">Periodo activo</span>
                        <span class="block text-xs mt-0.5" style="color: var(--color-secondary);">Visible para los estudiantes.</span>
                    </span>
                </label>

            </div>
        </div>

        {{-- ── Footer ── --}}
        <div class="flex items-center justify-between gap-2 px-6 py-4 flex-shrink-0"
            style="border-top: 1px solid var(--color-border);">

            <div>
                @if($periodId)
                    @php($periodModel = \App\Models\Period::find($periodId))
                    {{-- Solo se puede eliminar un periodo inactivo (ver deletePeriod()
                         en ManagesPeriods); si no cumple, ni se muestra el botón. --}}
                    @if($periodModel && ! $periodModel->is_active)
                        <button type="button" wire:click="confirmDelete({{ $periodId }})" class="btn-danger">
                            Eliminar
                        </button>
                    @endif
                @endif
            </div>

            <div class="flex items-center gap-2">
                <button wire:click="$set('isOpen', false)"
                        class="px-4 py-2 rounded-full text-sm transition-colors duration-150 text-[var(--color-secondary)] hover:text-[var(--color-primary-2)] hover:bg-[var(--sidebar-color-hover)]">
                    Cancelar
                </button>

                <button wire:click="save"
                        wire:loading.attr="disabled"
                        class="btn-primary disabled:opacity-60 disabled:cursor-not-allowed">
                    <span wire:loading.remove wire:target="save">
                        {{ $periodId ? 'Guardar' : 'Crear periodo' }}
                    </span>
                    <span wire:loading wire:target="save">Procesando...</span>
                </button>
            </div>
        </div>

    </div>
</flux:modal>
