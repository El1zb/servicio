{{-- =================== MODAL CREAR / EDITAR SEMESTRE =================== --}}
@if($isOpen)
    <flux:modal
        wire:model="isOpen"
        :dismissible="false"
        :closable="false"
        class="w-[95vw] sm:w-[90vw] lg:w-[720px] max-w-[95vw]"
        style="border-color: var(--color-border);">

        <div class="flex flex-col" style="max-height: 90dvh;">

            {{-- ── Header ── --}}
            <div class="flex items-center justify-between gap-4 px-6 py-5 flex-shrink-0"
                style="border-bottom: 1px solid var(--color-border);">
                <div class="min-w-0">
                    <h2 class="text-lg font-semibold leading-tight" style="color: var(--color-primary-2);">
                        {{ $semesterId ? 'Editar Semestre' : 'Nuevo Semestre' }}
                    </h2>
                    <p class="text-sm mt-0.5" style="color: var(--color-secondary);">
                        {{ $semesterId ? 'Actualiza la información del semestre' : 'Se le asignará este semestre a los estudiantes' }}
                    </p>
                </div>
            </div>

            {{-- ── Body ── --}}
            <div class="flex-1 overflow-y-auto px-6 py-5" style="background-color: var(--color-modal-bg);">
                <div class="space-y-5">

                    {{-- Nombre --}}
                    <div class="app-field">
                        <label class="app-field-label">Nombre del semestre <span style="color: #DC2626;">*</span></label>
                        <input type="text" wire:model.live.debounce.500ms="name" placeholder="Ej: Semestre 1" class="app-input w-full" wire:key="semester-name-{{ $formInstance }}">
                        @error('name') <p class="app-field-error">{{ $message }}</p> @enderror
                    </div>

                    {{-- Semestre activo --}}
                    <label class="individual-toggle" wire:click="$toggle('is_active')">
                        <span class="individual-toggle-check {{ $is_active ? 'is-checked' : '' }}">
                            <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3.5" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6L9 17l-5-5"/></svg>
                        </span>
                        <span class="min-w-0">
                            <span class="block text-sm font-medium" style="color: var(--color-primary-2);">Semestre activo</span>
                            <span class="block text-xs mt-0.5" style="color: var(--color-secondary);">Visible para los estudiantes.</span>
                        </span>
                    </label>

                </div>
            </div>

            {{-- ── Footer ── --}}
            <div class="flex items-center justify-between gap-2 px-6 py-4 flex-shrink-0"
                style="border-top: 1px solid var(--color-border);">

                <div>
                    @if($semesterId)
                        @if($this->canDeleteSemester)
                            <button type="button" wire:click="confirmDelete({{ $semesterId }})" class="btn-danger">
                                Eliminar
                            </button>
                        @endif
                    @endif
                </div>

                <div class="flex items-center gap-2">
                    <button wire:click="closeModal"
                            class="px-4 py-2 rounded-full text-sm transition-colors duration-150 text-[var(--color-secondary)] hover:text-[var(--color-primary-2)] hover:bg-[var(--sidebar-color-hover)]">
                        Cancelar
                    </button>

                    <button wire:click="save"
                            wire:loading.attr="disabled"
                            class="btn-primary disabled:opacity-60 disabled:cursor-not-allowed">
                        <span wire:loading.remove wire:target="save">
                            {{ $semesterId ? 'Guardar' : 'Crear semestre' }}
                        </span>
                        <span wire:loading wire:target="save">Procesando...</span>
                    </button>
                </div>
            </div>

        </div>
    </flux:modal>
@endif
