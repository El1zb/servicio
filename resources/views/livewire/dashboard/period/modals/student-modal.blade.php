{{-- =================== MODAL PERFIL ESTUDIANTE =================== --}}
@if($showModal && $selectedStudent)
    <flux:modal
        wire:model="showModal"
        :dismissible="false"
        class="w-[95vw] sm:w-[85vw] lg:w-[860px] xl:w-[960px] max-w-[95vw]">

        <div class="flex flex-col" style="max-height: 90vh;">

            {{-- ── Header ── --}}
            <div class="flex items-center justify-between px-6 py-5 flex-shrink-0"
                style="border-bottom: 1px solid var(--period-detail-border);">

                <div class="flex items-center gap-4 min-w-0">
                    <div class="min-w-0">
                        <h2 class="text-lg font-semibold leading-tight truncate"
                            style="color: var(--period-detail-text-primary);">
                            {{ $selectedStudent->name }}
                            {{ $selectedStudent->last_name_paterno }}
                            {{ $selectedStudent->last_name_materno }}
                        </h2>
                        <p class="text-sm mt-0.5 truncate" style="color: var(--period-detail-text-secondary);">
                            {{ $selectedStudent->control_number }}
                            @if($selectedStudent->career)
                                &nbsp;·&nbsp;{{ $selectedStudent->career->name }}
                            @endif
                        </p>
                    </div>
                </div>
            </div>

            {{-- ── Body ── --}}
            <div class="flex-1 overflow-y-auto px-6 py-5"
                style="background-color: var(--period-detail-card-bg);">

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">

                    {{-- ── Información Personal ── --}}
                    <div>
                        <p class="text-sm font-semibold uppercase tracking-widest mb-3"
                            style="color: var(--period-detail-accent);">Información Personal</p>

                        @if($editMode)
                            <div class="space-y-3">
                                <div>
                                    <label class="block text-sm mb-1" style="color: var(--period-detail-text-secondary);">Nombre(s)</label>
                                    <input type="text"
                                        class="w-full px-3 py-2 rounded-lg border text-base"
                                        style="background-color: var(--period-detail-bg); border-color: var(--period-detail-border); color: var(--period-detail-text-primary);"
                                        wire:model.defer="studentData.name">
                                </div>
                                <div class="grid grid-cols-2 gap-2">
                                    <div>
                                        <label class="block text-sm mb-1" style="color: var(--period-detail-text-secondary);">Ap. Paterno</label>
                                        <input type="text"
                                            class="w-full px-3 py-2 rounded-lg border text-base"
                                            style="background-color: var(--period-detail-bg); border-color: var(--period-detail-border); color: var(--period-detail-text-primary);"
                                            wire:model.defer="studentData.last_name_paterno">
                                    </div>
                                    <div>
                                        <label class="block text-sm mb-1" style="color: var(--period-detail-text-secondary);">Ap. Materno</label>
                                        <input type="text"
                                            class="w-full px-3 py-2 rounded-lg border text-base"
                                            style="background-color: var(--period-detail-bg); border-color: var(--period-detail-border); color: var(--period-detail-text-primary);"
                                            wire:model.defer="studentData.last_name_materno">
                                    </div>
                                </div>
                                <div class="grid grid-cols-2 gap-2">
                                    <div>
                                        <label class="block text-sm mb-1" style="color: var(--period-detail-text-secondary);">CURP</label>
                                        <input type="text"
                                            class="w-full px-3 py-2 rounded-lg border text-base"
                                            style="background-color: var(--period-detail-bg); border-color: var(--period-detail-border); color: var(--period-detail-text-primary);"
                                            wire:model.defer="studentData.curp">
                                    </div>
                                    <div>
                                        <label class="block text-sm mb-1" style="color: var(--period-detail-text-secondary);">RFC</label>
                                        <input type="text"
                                            class="w-full px-3 py-2 rounded-lg border text-base"
                                            style="background-color: var(--period-detail-bg); border-color: var(--period-detail-border); color: var(--period-detail-text-primary);"
                                            wire:model.defer="studentData.rfc">
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-sm mb-1" style="color: var(--period-detail-text-secondary);">Teléfono</label>
                                    <input type="tel"
                                        class="w-full px-3 py-2 rounded-lg border text-base"
                                        style="background-color: var(--period-detail-bg); border-color: var(--period-detail-border); color: var(--period-detail-text-primary);"
                                        wire:model.defer="studentData.phone">
                                </div>
                                <div>
                                    <label class="block text-sm mb-1" style="color: var(--period-detail-text-secondary);">Correo Personal</label>
                                    <input type="email"
                                        class="w-full px-3 py-2 rounded-lg border text-base"
                                        style="background-color: var(--period-detail-bg); border-color: var(--period-detail-border); color: var(--period-detail-text-primary);"
                                        wire:model.defer="studentData.personal_email">
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
                                        style="background-color: var(--period-detail-bg);">
                                        <span class="text-sm" style="color: var(--period-detail-text-secondary);">{{ $field['label'] }}</span>
                                        <span class="text-sm font-medium ml-4 text-right truncate max-w-[60%]"
                                            style="color: var(--period-detail-text-primary);">
                                            {{ $field['value'] ?: '—' }}
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    {{-- ── Datos Académicos ── --}}
                    <div>
                        <p class="text-sm font-semibold uppercase tracking-widest mb-3"
                            style="color: var(--period-detail-accent);">Datos Académicos</p>

                        @if($editMode)
                            <div class="space-y-3">
                                <div>
                                    <label class="block text-sm mb-1" style="color: var(--period-detail-text-secondary);">Número de Control</label>
                                    <input type="text"
                                        class="w-full px-3 py-2 rounded-lg border text-base"
                                        style="background-color: var(--period-detail-bg); border-color: var(--period-detail-border); color: var(--period-detail-text-primary);"
                                        wire:model.defer="studentData.control_number">
                                </div>
                                <div>
                                    <label class="block text-sm mb-1" style="color: var(--period-detail-text-secondary);">Correo Institucional</label>
                                    <input type="email"
                                        class="w-full px-3 py-2 rounded-lg border text-base"
                                        style="background-color: var(--period-detail-bg); border-color: var(--period-detail-border); color: var(--period-detail-text-primary);"
                                        wire:model.defer="studentData.institutional_email">
                                </div>
                                <div class="grid grid-cols-2 gap-2">
                                    <flux:select label="Sistema" wire:model.defer="studentData.system" class="w-full text-base"
                                        style="background-color: var(--period-detail-bg); border-color: var(--period-detail-border); color: var(--period-detail-text-primary);">
                                        <option value="Escolarizado">Escolarizado</option>
                                        <option value="Sabatino">Sabatino</option>
                                    </flux:select>
                                    <flux:select label="Campus" wire:model.defer="studentData.campus_id" class="w-full text-base"
                                        style="background-color: var(--period-detail-bg); border-color: var(--period-detail-border); color: var(--period-detail-text-primary);">
                                        @foreach($campuses as $campus)
                                            <option value="{{ $campus->id }}">{{ $campus->name }}</option>
                                        @endforeach
                                    </flux:select>
                                </div>
                                <div class="grid grid-cols-2 gap-2">
                                    <flux:select label="Semestre" wire:model.defer="studentData.semester_id" class="w-full text-base"
                                        style="background-color: var(--period-detail-bg); border-color: var(--period-detail-border); color: var(--period-detail-text-primary);">
                                        @foreach($semesters as $semester)
                                            <option value="{{ $semester->id }}">{{ $semester->name }}</option>
                                        @endforeach
                                    </flux:select>
                                    <flux:select label="Carrera" wire:model.defer="studentData.career_id" class="w-full text-base"
                                        style="background-color: var(--period-detail-bg); border-color: var(--period-detail-border); color: var(--period-detail-text-primary);">
                                        @foreach($careers as $career)
                                            <option value="{{ $career->id }}">{{ $career->name }}</option>
                                        @endforeach
                                    </flux:select>
                                </div>
                                <div>
                                    <label class="block text-sm mb-1" style="color: var(--period-detail-text-secondary);">Avance Reticular (%)</label>
                                    <input type="number" min="0" max="100"
                                        class="w-full px-3 py-2 rounded-lg border text-base"
                                        style="background-color: var(--period-detail-bg); border-color: var(--period-detail-border); color: var(--period-detail-text-primary);"
                                        wire:model.defer="studentData.reticular_progress">
                                </div>
                            </div>
                        @else
                            <div class="space-y-2">
                                @foreach([
                                    ['label' => 'Correo Institucional', 'value' => $selectedStudent->institutional_email],
                                    ['label' => 'Sistema',              'value' => $selectedStudent->system],
                                    ['label' => 'Semestre',             'value' => $selectedStudent->semester?->name],
                                    ['label' => 'Campus',               'value' => $selectedStudent->campus?->name],
                                    ['label' => 'Periodo',              'value' => $selectedStudent->period?->name],
                                    ['label' => 'Avance Reticular',     'value' => ($selectedStudent->reticular_progress ?? 0) . '%'],
                                ] as $field)
                                    <div class="flex items-center justify-between py-2.5 px-3 rounded-lg"
                                        style="background-color: var(--period-detail-bg);">
                                        <span class="text-sm" style="color: var(--period-detail-text-secondary);">{{ $field['label'] }}</span>
                                        <span class="text-sm font-medium ml-4 text-right truncate max-w-[60%]"
                                            style="color: var(--period-detail-text-primary);">
                                            {{ $field['value'] ?: '—' }}
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>

                </div>
            </div>

            {{-- ── Footer ── --}}
            <div class="flex items-center justify-between gap-3 px-6 py-4 flex-shrink-0"
                style="border-top: 1px solid var(--period-detail-border); background-color: var(--period-detail-card-bg);">

                {{-- Left: Cancel / Close --}}
                <div>
                    @if($editMode)
                        <button wire:click="cancelEdit"
                            class="px-4 py-2 rounded-lg text-sm transition-all duration-200 hover:-translate-y-0.5"
                            style="color: var(--period-detail-text-secondary); background-color: transparent;"
                            onmouseover="this.style.color='var(--period-detail-text-primary)'; this.style.backgroundColor='var(--period-detail-border)'"
                            onmouseout="this.style.color='var(--period-detail-text-secondary)'; this.style.backgroundColor='transparent'">
                            Cancelar
                        </button>
                    @else
                        <button wire:click="closeModal"
                            class="px-4 py-2 rounded-lg text-sm transition-all duration-200 hover:-translate-y-0.5"
                            style="color: var(--period-detail-text-secondary); background-color: transparent;"
                            onmouseover="this.style.color='var(--period-detail-text-primary)'; this.style.backgroundColor='var(--period-detail-border)'"
                            onmouseout="this.style.color='var(--period-detail-text-secondary)'; this.style.backgroundColor='transparent'">
                            Cerrar
                        </button>
                    @endif
                </div>

                {{-- Right: Actions --}}
                <div class="flex items-center gap-2">
                    @if($editMode)
                        <button wire:click="updateStudent"
                            class="px-5 py-2 rounded-lg text-sm font-medium transition-all duration-200 hover:-translate-y-0.5 hover:shadow-lg"
                            style="background-color: var(--period-detail-brand-primary); color: var(--period-detail-text-icon);">
                            Guardar
                        </button>
                    @else
                        <button wire:click="editStudent({{ $selectedStudent->id }})"
                            class="px-4 py-2 rounded-lg text-sm transition-all duration-200 hover:-translate-y-0.5"
                            style="background-color: var(--period-detail-brand-primary); color: var(--period-detail-text-icon);">
                            Editar
                        </button>

                        <button wire:click="approve({{ $selectedStudent->id }})"
                            class="px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200 hover:-translate-y-0.5 hover:shadow-md"
                            style="background-color: var(--period-detail-btn-approve-bg); color: var(--period-detail-btn-approve-text);"
                            onmouseover="this.style.opacity='0.85'"
                            onmouseout="this.style.opacity='1'">
                            Aprobar
                        </button>

                        <button wire:click="reject({{ $selectedStudent->id }})"
                            class="px-4 py-2 rounded-lg text-base font-medium transition-all duration-200 hover:-translate-y-0.5 hover:shadow-md"
                            style="background-color: var(--period-detail-btn-reject-bg); color: var(--period-detail-btn-reject-text);"
                            onmouseover="this.style.opacity='0.85'"
                            onmouseout="this.style.opacity='1'">
                            Rechazar
                        </button>
                    @endif
                </div>

            </div>

        </div>
    </flux:modal>
@endif