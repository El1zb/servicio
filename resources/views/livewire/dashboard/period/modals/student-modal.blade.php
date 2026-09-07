{{-- =================== MODAL PERFIL ESTUDIANTE / VISOR RÁPIDO =================== --}}
@if($showModal && $selectedStudent)
    <flux:modal
        wire:model="showModal"
        :dismissible="false"
        :closable="false"
        class="w-[95vw] sm:w-[90vw] lg:w-[720px] max-w-[95vw]"
        style="border-color: var(--color-border);">

        <div class="flex flex-col" style="max-height: 90vh;"
            x-data
            @keydown.window="
                const el = document.activeElement;
                const typing = el && (el.tagName === 'INPUT' || el.tagName === 'TEXTAREA' || el.isContentEditable);
                if ($wire.showModal && !typing) {
                    if ($event.key === 'ArrowLeft') {
                        const btn = document.querySelector('button[title=\'Estudiante anterior\']');
                        if (btn && !btn.disabled) btn.click();
                    } else if ($event.key === 'ArrowRight') {
                        const btn = document.querySelector('button[title=\'Siguiente estudiante\']');
                        if (btn && !btn.disabled) btn.click();
                    }
                }
            ">

            {{-- ── Header ── --}}
            <div class="flex items-center gap-4 px-6 py-5 flex-shrink-0"
                style="border-bottom: 1px solid var(--color-border);">

                <button type="button" wire:click="closeModal" class="review-icon-btn flex-shrink-0" style="width: 32px; height: 32px;">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 18L18 6M6 6l12 12"/></svg>
                </button>

                <div class="flex-1 min-w-0 flex justify-center text-center">
                    <div class="min-w-0">
                        <h2 class="text-lg font-semibold leading-tight truncate"
                            style="color: var(--color-primary-2);">
                            {{ $selectedStudent->name }}
                            {{ $selectedStudent->last_name_paterno }}
                            {{ $selectedStudent->last_name_materno }}
                        </h2>
                        <p class="text-sm mt-0.5 truncate" style="color: var(--color-secondary);">
                            {{ $selectedStudent->control_number }}
                            @if($selectedStudent->career)
                                &nbsp;·&nbsp;{{ $selectedStudent->career->name }}
                            @endif
                        </p>
                    </div>
                </div>

                <span class="status-badge flex-shrink-0 {{ $selectedStudent->status === 'aprobado' ? 'status-badge--approved' : ($selectedStudent->status === 'rechazado' ? 'status-badge--rejected' : 'status-badge--pending') }}">
                    <span class="status-badge-dot"></span>
                    {{ $selectedStudent->status === 'aprobado' ? 'Aprobado' : ($selectedStudent->status === 'rechazado' ? 'Rechazado' : 'Pendiente') }}
                </span>
            </div>

            {{-- ── Body ── --}}
            <div class="flex-1 overflow-y-auto px-6 py-5"
                style="background-color: var(--color-modal-bg);">

                @if($selectedStudent->status === 'rechazado' && $selectedStudent->rejection_reason)
                    <div class="mb-5">
                        <p class="text-sm font-bold mb-3" style="color: #DC2626;">Motivo del rechazo</p>
                        <p class="text-sm" style="color: var(--color-primary-2);">{{ $selectedStudent->rejection_reason }}</p>
                    </div>
                @endif

                <div class="space-y-6">

                    {{-- ── Información Personal ── --}}
                    <div>
                        <p class="text-sm font-bold mb-3"
                            style="color: var(--color-primary-2);">Información personal</p>

                        @if($editMode)
                            <div class="space-y-3">
                                <div>
                                    <label class="app-field-label">Nombre(s) <span style="color: #DC2626;">*</span></label>
                                    <input type="text" class="app-input w-full" wire:model.live.debounce.500ms="studentData.name">
                                    @error('studentData.name') <p class="app-field-error">{{ $message }}</p> @enderror
                                </div>
                                <div class="grid grid-cols-2 gap-2">
                                    <div>
                                        <label class="app-field-label">Ap. Paterno <span style="color: #DC2626;">*</span></label>
                                        <input type="text" class="app-input w-full" wire:model.live.debounce.500ms="studentData.last_name_paterno">
                                        @error('studentData.last_name_paterno') <p class="app-field-error">{{ $message }}</p> @enderror
                                    </div>
                                    <div>
                                        <label class="app-field-label">Ap. Materno <span style="color: #DC2626;">*</span></label>
                                        <input type="text" class="app-input w-full" wire:model.live.debounce.500ms="studentData.last_name_materno">
                                        @error('studentData.last_name_materno') <p class="app-field-error">{{ $message }}</p> @enderror
                                    </div>
                                </div>
                                <div class="grid grid-cols-2 gap-2">
                                    <div>
                                        <label class="app-field-label">CURP <span style="color: #DC2626;">*</span></label>
                                        <input type="text" class="app-input w-full" wire:model.live.debounce.500ms="studentData.curp">
                                        @error('studentData.curp') <p class="app-field-error">{{ $message }}</p> @enderror
                                    </div>
                                    <div>
                                        <label class="app-field-label">RFC</label>
                                        <input type="text" class="app-input w-full" wire:model.defer="studentData.rfc">
                                    </div>
                                </div>
                                <div>
                                    <label class="app-field-label">Teléfono <span style="color: #DC2626;">*</span></label>
                                    <input type="tel" class="app-input w-full" wire:model.live.debounce.500ms="studentData.phone">
                                    @error('studentData.phone') <p class="app-field-error">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label class="app-field-label">Correo Personal <span style="color: #DC2626;">*</span></label>
                                    <input type="email" class="app-input w-full" wire:model.live.debounce.500ms="studentData.personal_email">
                                    @error('studentData.personal_email') <p class="app-field-error">{{ $message }}</p> @enderror
                                </div>
                            </div>
                        @else
                            <div class="space-y-2">
                                @foreach([
                                    ['label' => 'CURP',            'value' => $selectedStudent->curp],
                                    ['label' => 'RFC',             'value' => $selectedStudent->rfc],
                                    ['label' => 'Teléfono',        'value' => $selectedStudent->phone],
                                    ['label' => 'Correo Personal', 'value' => $selectedStudent->personal_email],
                                ] as $field)
                                    <div class="flex items-center justify-between py-2.5 px-3 rounded-lg"
                                        style="background-color: var(--color-bg);">
                                        <span class="text-sm" style="color: var(--color-secondary);">{{ $field['label'] }}</span>
                                        <span class="text-sm font-medium ml-4 text-right truncate max-w-[60%]"
                                            style="color: var(--color-primary-2);">
                                            {{ $field['value'] ?: '—' }}
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    {{-- ── Datos Académicos ── --}}
                    <div>
                        <p class="text-sm font-bold mb-3"
                            style="color: var(--color-primary-2);">Datos académicos</p>

                        @if($editMode)
                            <div class="space-y-3">
                                <div>
                                    <label class="app-field-label">Número de Control <span style="color: #DC2626;">*</span></label>
                                    <input type="text" class="app-input w-full" wire:model.live.debounce.500ms="studentData.control_number">
                                    @error('studentData.control_number') <p class="app-field-error">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label class="app-field-label">Correo Institucional <span style="color: #DC2626;">*</span></label>
                                    <input type="email" class="app-input w-full" wire:model.live.debounce.500ms="studentData.institutional_email">
                                    @error('studentData.institutional_email') <p class="app-field-error">{{ $message }}</p> @enderror
                                </div>
                                <div class="grid grid-cols-2 gap-2">
                                    <div>
                                        <label class="app-field-label">Sistema <span style="color: #DC2626;">*</span></label>
                                        <x-select wire:key="system-{{ $editFormInstance }}"
                                            wire-model="studentData.system"
                                            :value="$studentData['system'] ?? null"
                                            :options="['Escolarizado' => 'Escolarizado', 'Sabatino' => 'Sabatino']" />
                                        @error('studentData.system') <p class="app-field-error">{{ $message }}</p> @enderror
                                    </div>
                                    <div>
                                        <label class="app-field-label">Campus <span style="color: #DC2626;">*</span></label>
                                        <x-select wire:key="campus-{{ $editFormInstance }}"
                                            wire-model="studentData.campus_id"
                                            :value="$studentData['campus_id'] ?? null"
                                            :options="$campuses->pluck('name', 'id')" />
                                        @error('studentData.campus_id') <p class="app-field-error">{{ $message }}</p> @enderror
                                    </div>
                                </div>
                                <div class="grid grid-cols-2 gap-2">
                                    <div>
                                        <label class="app-field-label">Semestre <span style="color: #DC2626;">*</span></label>
                                        <x-select wire:key="semester-{{ $editFormInstance }}"
                                            wire-model="studentData.semester_id"
                                            :value="$studentData['semester_id'] ?? null"
                                            :options="$semesters->pluck('name', 'id')" />
                                        @error('studentData.semester_id') <p class="app-field-error">{{ $message }}</p> @enderror
                                    </div>
                                    <div>
                                        <label class="app-field-label">Carrera <span style="color: #DC2626;">*</span></label>
                                        <x-select wire:key="career-{{ $editFormInstance }}"
                                            wire-model="studentData.career_id"
                                            :value="$studentData['career_id'] ?? null"
                                            :options="$careers->pluck('name', 'id')" />
                                        @error('studentData.career_id') <p class="app-field-error">{{ $message }}</p> @enderror
                                    </div>
                                </div>
                                <div>
                                    <label class="app-field-label">Avance Reticular (%) <span style="color: #DC2626;">*</span></label>
                                    <input type="number" min="0" max="100" class="app-input w-full" wire:model.live.debounce.500ms="studentData.reticular_progress">
                                    @error('studentData.reticular_progress') <p class="app-field-error">{{ $message }}</p> @enderror
                                </div>
                            </div>
                        @else
                            <div class="space-y-2">
                                @foreach([
                                    ['label' => 'Correo Institucional', 'value' => $selectedStudent->institutional_email],
                                    ['label' => 'Sistema',              'value' => $selectedStudent->system],
                                    ['label' => 'Semestre',             'value' => $selectedStudent->semester?->name],
                                    ['label' => 'Campus',               'value' => $selectedStudent->campus?->name],
                                    ['label' => 'Avance Reticular',     'value' => ($selectedStudent->reticular_progress ?? 0) . '%'],
                                ] as $field)
                                    <div class="flex items-center justify-between py-2.5 px-3 rounded-lg"
                                        style="background-color: var(--color-bg);">
                                        <span class="text-sm" style="color: var(--color-secondary);">{{ $field['label'] }}</span>
                                        <span class="text-sm font-medium ml-4 text-right truncate max-w-[60%]"
                                            style="color: var(--color-primary-2);">
                                            {{ $field['value'] ?: '—' }}
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>

                </div>

                {{-- ── Rechazo inline (sin modal aparte) ── --}}
                @if($isRejecting)
                    <div class="mt-5">
                        <label class="app-field-label text-sm font-bold" style="color: #DC2626;">Motivo del rechazo</label>
                        <textarea
                            wire:model="rejectionReason"
                            rows="3"
                            placeholder="Describe el motivo de forma clara, el estudiante lo verá..."
                            class="app-input w-full"
                            style="height: auto; padding-top: 10px; padding-bottom: 10px; border-radius: 12px;"></textarea>
                        @error('rejectionReason')
                            <p class="app-field-error">{{ $message }}</p>
                        @enderror
                    </div>
                @endif
            </div>

            {{-- ── Footer ── --}}
            <div class="flex items-center justify-between gap-3 px-6 py-4 flex-shrink-0"
                style="border-top: 1px solid var(--color-border);">

                {{-- Izquierda: navegación entre pendientes --}}
                <div class="flex items-center gap-2">
                    @if(!$editMode && !$isRejecting && ($previousPendingStudent || $nextPendingStudent))
                        <button wire:click="navigateToPreviousPending" @disabled(!$previousPendingStudent) title="Estudiante anterior"
                                class="w-8 h-8 flex items-center justify-center rounded-full disabled:opacity-30 disabled:cursor-not-allowed"
                                style="color: var(--color-icon); background-color: transparent;"
                                onmouseover="if(!this.disabled){this.style.backgroundColor='var(--sidebar-color-hover)'}"
                                onmouseout="this.style.backgroundColor='transparent'">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 19l-7-7 7-7"/></svg>
                        </button>
                        <button wire:click="navigateToNextPending" @disabled(!$nextPendingStudent) title="Siguiente estudiante"
                                class="w-8 h-8 flex items-center justify-center rounded-full disabled:opacity-30 disabled:cursor-not-allowed"
                                style="color: var(--color-icon); background-color: transparent;"
                                onmouseover="if(!this.disabled){this.style.backgroundColor='var(--sidebar-color-hover)'}"
                                onmouseout="this.style.backgroundColor='transparent'">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 5l7 7-7 7"/></svg>
                        </button>
                        @if($pendingQueueTotal > 0)
                            <span class="text-xs" style="color: var(--color-secondary);">
                                {{ $pendingQueuePosition }} de {{ $pendingQueueTotal }}
                            </span>
                        @endif
                    @endif
                </div>

                {{-- Derecha: acciones --}}
                <div class="flex items-center gap-2">
                    @if($editMode)
                        <button wire:click="cancelEdit"
                                class="px-4 py-2 rounded-full text-sm transition-colors duration-150 text-[var(--color-secondary)] hover:text-[var(--color-primary-2)] hover:bg-[var(--sidebar-color-hover)]">
                            Cancelar
                        </button>
                        <button wire:click="updateStudent" class="btn-primary">
                            Guardar
                        </button>
                    @elseif($isRejecting)
                        <button wire:click="cancelReject"
                                class="px-4 py-2 rounded-full text-sm transition-colors duration-150 text-[var(--color-secondary)] hover:text-[var(--color-primary-2)] hover:bg-[var(--sidebar-color-hover)]">
                            Cancelar
                        </button>
                        <button wire:click="confirmReject" class="btn-danger">
                            Confirmar rechazo
                        </button>
                    @else
                        <button wire:click="editStudent({{ $selectedStudent->id }})"
                                class="px-4 py-2 rounded-full text-sm transition-colors duration-150 bg-[var(--color-bg)] text-[var(--color-primary-2)] hover:bg-[var(--color-border-hover)]">
                            Editar
                        </button>

                        @if($selectedStudent->status === 'pendiente')
                            <button wire:click="startReject" class="btn-danger">
                                Rechazar
                            </button>
                            <button wire:click="approve({{ $selectedStudent->id }})" class="btn-success">
                                Aprobar
                            </button>
                        @endif
                    @endif
                </div>

            </div>

        </div>
    </flux:modal>
@endif
