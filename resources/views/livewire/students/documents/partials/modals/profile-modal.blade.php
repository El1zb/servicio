{{-- =================== MODAL PERFIL DEL ESTUDIANTE (2 pasos) =================== --}}
@if($isProfileModalOpen)
    <flux:modal
        wire:model="isProfileModalOpen"
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
                        {{ $profileStep === 1 ? 'Datos personales' : 'Datos académicos' }}
                    </h2>
                    <p class="text-sm mt-0.5" style="color: var(--color-secondary);">
                        @if($profileTotalSteps > 1)
                            Paso {{ $profileStep }} de {{ $profileTotalSteps }}
                        @else
                            Actualiza tu información personal
                        @endif
                    </p>
                </div>
            </div>

            {{-- ── Body ──
                 pb-16 extra: los combobox de este paso abren siempre hacia
                 abajo y nunca hacia arriba; ese colchón le da espacio a los
                 que están en la última fila para desplegarse sin quedar
                 cortados por la esquina del modal — el scroll (overflow-y
                 arriba) solo se activa/aparece si ese espacio realmente
                 se llega a necesitar. --}}
            <div class="flex-1 overflow-y-auto px-6 pt-5 pb-16" style="background-color: var(--color-modal-bg);">

                @if($profileStep === 1)
                    {{-- wire:key distinto por paso: sin esto, Livewire puede
                         morfear los inputs del paso 1 en los del paso 2 en
                         vez de reemplazarlos, arrastrando el valor aún sin
                         confirmar (debounce) de un campo hacia otra
                         propiedad completamente distinta. --}}
                    <div wire:key="profile-step-1-{{ $profileFormInstance }}" class="space-y-5">

                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            <div class="app-field">
                                <label class="app-field-label">Nombre(s) <span style="color: #DC2626;">*</span></label>
                                <input type="text" wire:model.live.debounce.500ms="name" class="app-input w-full"
                                    pattern="[A-Za-zÁÉÍÓÚáéíóúÑñ\s]+"
                                    oninput="this.value = this.value.replace(/[^A-Za-zÁÉÍÓÚáéíóúÑñ\s]/g, '')">
                                @error('name') <p class="app-field-error">{{ $message }}</p> @enderror
                            </div>
                            <div class="app-field">
                                <label class="app-field-label">Apellido Paterno <span style="color: #DC2626;">*</span></label>
                                <input type="text" wire:model.live.debounce.500ms="last_name_paterno" class="app-input w-full"
                                    pattern="[A-Za-zÁÉÍÓÚáéíóúÑñ\s]+"
                                    oninput="this.value = this.value.replace(/[^A-Za-zÁÉÍÓÚáéíóúÑñ\s]/g, '')">
                                @error('last_name_paterno') <p class="app-field-error">{{ $message }}</p> @enderror
                            </div>
                            <div class="app-field">
                                <label class="app-field-label">Apellido Materno <span style="color: #DC2626;">*</span></label>
                                <input type="text" wire:model.live.debounce.500ms="last_name_materno" class="app-input w-full"
                                    pattern="[A-Za-zÁÉÍÓÚáéíóúÑñ\s]+"
                                    oninput="this.value = this.value.replace(/[^A-Za-zÁÉÍÓÚáéíóúÑñ\s]/g, '')">
                                @error('last_name_materno') <p class="app-field-error">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div class="app-field">
                                <label class="app-field-label">CURP <span style="color: #DC2626;">*</span></label>
                                <input type="text" wire:model.live.debounce.500ms="curp" class="app-input w-full" style="text-transform: uppercase;"
                                    maxlength="18"
                                    oninput="this.value = this.value.toUpperCase().replace(/[^A-Z0-9]/g, '')">
                                @error('curp') <p class="app-field-error">{{ $message }}</p> @enderror
                            </div>
                            <div class="app-field">
                                <label class="app-field-label">RFC</label>
                                <input type="text" wire:model.live.debounce.500ms="rfc" class="app-input w-full" style="text-transform: uppercase;"
                                    maxlength="13"
                                    oninput="this.value = this.value.toUpperCase().replace(/[^A-Z0-9Ñ&]/g, '')">
                                @error('rfc') <p class="app-field-error">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div class="app-field">
                                <label class="app-field-label">Teléfono <span style="color: #DC2626;">*</span></label>
                                <input type="text" wire:model.live.debounce.500ms="phone" placeholder="Ej. 0000000000" class="app-input w-full"
                                    maxlength="10"
                                    oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                                @error('phone') <p class="app-field-error">{{ $message }}</p> @enderror
                            </div>
                            <div class="app-field">
                                <label class="app-field-label">Correo Personal <span style="color: #DC2626;">*</span></label>
                                <input type="email" wire:model.live.debounce.500ms="personal_email" placeholder="Ej. personal@ejemplo.com" class="app-input w-full"
                                    oninput="this.value = this.value.toLowerCase()">
                                @error('personal_email') <p class="app-field-error">{{ $message }}</p> @enderror
                            </div>
                        </div>

                    </div>
                @else
                    <div wire:key="profile-step-2-{{ $profileFormInstance }}" class="space-y-5">

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div class="app-field">
                                <label class="app-field-label">Número de Control <span style="color: #DC2626;">*</span></label>
                                <input type="text" wire:model.live.debounce.500ms="control_number" class="app-input w-full" style="text-transform: uppercase;"
                                    oninput="this.value = this.value.replace(/[^A-Za-z0-9]/g, '').toUpperCase()">
                                @error('control_number') <p class="app-field-error">{{ $message }}</p> @enderror
                            </div>
                            <div class="app-field">
                                <label class="app-field-label">Correo Institucional <span style="color: #DC2626;">*</span></label>
                                <input type="email" wire:model.live.debounce.500ms="institutional_email" placeholder="Ej. l225q0000@itsco.edu.mx" class="app-input w-full"
                                    oninput="this.value = this.value.toLowerCase()">
                                @error('institutional_email') <p class="app-field-error">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div class="app-field">
                                <label class="app-field-label">Campus <span style="color: #DC2626;">*</span></label>
                                <x-select wire:key="profile-campus-{{ $profileFormInstance }}"
                                    wire-model="campus_id"
                                    :value="$campus_id"
                                    :options="$profileCampuses->pluck('name', 'id')" />
                                @error('campus_id') <p class="app-field-error">{{ $message }}</p> @enderror
                            </div>
                            <div class="app-field">
                                <label class="app-field-label">Carrera <span style="color: #DC2626;">*</span></label>
                                <x-select wire:key="profile-career-{{ $profileFormInstance }}-{{ $campus_id }}"
                                    wire-model="career_id"
                                    :value="$career_id"
                                    :options="$profileCareers->pluck('name', 'id')"
                                    :placeholder="$campus_id ? 'Seleccionar' : 'Elige un campus primero'" />
                                @error('career_id') <p class="app-field-error">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div class="app-field">
                                <label class="app-field-label">Sistema <span style="color: #DC2626;">*</span></label>
                                <x-select wire:key="profile-system-{{ $profileFormInstance }}"
                                    wire-model="system"
                                    :value="$system"
                                    :options="['Escolarizado' => 'Escolarizado', 'Sabatino' => 'Sabatino']" />
                                @error('system') <p class="app-field-error">{{ $message }}</p> @enderror
                            </div>
                            <div class="app-field">
                                <label class="app-field-label">Semestre <span style="color: #DC2626;">*</span></label>
                                <x-select wire:key="profile-semester-{{ $profileFormInstance }}"
                                    wire-model="semester_id"
                                    :value="$semester_id"
                                    :options="$profileSemesters->pluck('name', 'id')" />
                                @error('semester_id') <p class="app-field-error">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div class="app-field">
                                <label class="app-field-label">Periodo <span style="color: #DC2626;">*</span></label>
                                <x-select wire:key="profile-period-{{ $profileFormInstance }}"
                                    wire-model="period_id"
                                    :value="$period_id"
                                    :options="$profilePeriods->pluck('name', 'id')" />
                                @error('period_id') <p class="app-field-error">{{ $message }}</p> @enderror
                            </div>
                            <div class="app-field">
                                <label class="app-field-label">Avance Reticular (%) <span style="color: #DC2626;">*</span></label>
                                <input type="number" wire:model.live.debounce.500ms="reticular_progress" min="0" max="100" step="0.01" placeholder="Ej. 70.00" class="app-input w-full">
                                @error('reticular_progress') <p class="app-field-error">{{ $message }}</p> @enderror
                            </div>
                        </div>

                    </div>
                @endif
            </div>

            {{-- ── Footer ── --}}
            <div class="flex items-center justify-between gap-2 px-6 py-4 flex-shrink-0"
                style="border-top: 1px solid var(--color-border);">

                <div>
                    @if($profileStep === 2)
                        <button type="button" wire:click="prevProfileStep"
                                class="px-4 py-2 rounded-full text-sm transition-colors duration-150 text-[var(--color-secondary)] hover:text-[var(--color-primary-2)] hover:bg-[var(--sidebar-color-hover)]">
                            Atrás
                        </button>
                    @endif
                </div>

                <div class="flex items-center gap-2">
                    <button wire:click="closeProfileModal"
                            class="px-4 py-2 rounded-full text-sm transition-colors duration-150 text-[var(--color-secondary)] hover:text-[var(--color-primary-2)] hover:bg-[var(--sidebar-color-hover)]">
                        Cancelar
                    </button>

                    @if($profileStep < $profileTotalSteps)
                        <button wire:click="nextProfileStep"
                                wire:loading.attr="disabled"
                                class="btn-primary disabled:opacity-60 disabled:cursor-not-allowed">
                            <span wire:loading.remove wire:target="nextProfileStep">Siguiente</span>
                            <span wire:loading wire:target="nextProfileStep">Procesando...</span>
                        </button>
                    @else
                        <button wire:click="saveProfile"
                                wire:loading.attr="disabled"
                                class="btn-primary disabled:opacity-60 disabled:cursor-not-allowed">
                            <span wire:loading.remove wire:target="saveProfile">{{ $profileIsNewSubmission ? 'Enviar' : 'Guardar' }}</span>
                            <span wire:loading wire:target="saveProfile">Procesando...</span>
                        </button>
                    @endif
                </div>
            </div>

        </div>
    </flux:modal>
@endif
