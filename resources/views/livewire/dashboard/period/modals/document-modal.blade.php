{{-- =================== MODAL CREAR / EDITAR DOCUMENTO BASE =================== --}}
@if($isDocumentModalOpen)
    <flux:modal
        wire:model="isDocumentModalOpen"
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
                        {{ $editingDocumentId ? 'Editar Documento Base' : 'Nuevo Documento Base' }}
                    </h2>
                    <p class="text-sm mt-0.5" style="color: var(--color-secondary);">
                        {{ $editingDocumentId ? 'Actualiza la información del documento' : 'Se le asignará este documento a los estudiantes' }}
                    </p>
                </div>
            </div>

            {{-- ── Body ── --}}
            <div class="flex-1 overflow-y-auto px-6 py-5" style="background-color: var(--color-modal-bg);">
                <div class="space-y-5">

                    {{-- Nombre --}}
                    <div class="app-field">
                        <label class="app-field-label">Nombre del documento <span style="color: #DC2626;">*</span></label>
                        <input type="text" wire:model.live.debounce.500ms="documentName" placeholder="Ej: Anexo 10. Plan de Trabajo" class="app-input w-full">
                        @error('documentName') <p class="app-field-error">{{ $message }}</p> @enderror
                    </div>

                    {{-- Modo de carga --}}
                    <div>
                        <label class="app-field-label mb-2 block">Modo de carga <span style="color: #DC2626;">*</span></label>
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                            @foreach([
                                'bidirectional' => ['label' => 'Bidireccional', 'desc' => 'Admin y estudiantes pueden subir'],
                                'user_only'     => ['label' => 'Solo Estudiantes', 'desc' => 'Solo estudiantes pueden subir'],
                                'admin_only'    => ['label' => 'Solo Admin', 'desc' => 'Solo administradores pueden subir'],
                            ] as $modeValue => $modeData)
                                <label class="upload-mode-card">
                                    <input type="radio" wire:model.live="documentUploadMode" value="{{ $modeValue }}" class="sr-only">
                                    <span class="upload-mode-card-title">{{ $modeData['label'] }}</span>
                                    <span class="upload-mode-card-desc">{{ $modeData['desc'] }}</span>
                                </label>
                            @endforeach
                        </div>
                        @error('documentUploadMode') <p class="app-field-error">{{ $message }}</p> @enderror
                    </div>

                    {{-- Fecha límite y tamaño (si no es admin_only) --}}
                    @if($documentUploadMode !== 'admin_only')
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div class="app-field">
                                <label class="app-field-label">Fecha límite <span style="color: #DC2626;">*</span></label>
                                <x-date-picker
                                    wire:key="document-deadline-{{ $documentFormInstance }}"
                                    wire-model="documentDeadline"
                                    :value="$documentDeadline"
                                    :min="$period->start_date ? \Carbon\Carbon::parse($period->start_date)->format('Y-m-d') : null"
                                    :max="$period->end_date ? \Carbon\Carbon::parse($period->end_date)->format('Y-m-d') : null" />
                                @error('documentDeadline') <p class="app-field-error">{{ $message }}</p> @enderror
                            </div>
                            <div class="app-field">
                                <label class="app-field-label">Tamaño máximo (KB) <span style="color: #DC2626;">*</span></label>
                                <input type="number" wire:model.live.debounce.500ms="maxSize" min="1" max="20480" class="app-input w-full">
                                @error('maxSize') <p class="app-field-error">{{ $message }}</p> @enderror
                            </div>
                        </div>
                    @endif

                    {{-- Documento individual (solo admin_only) --}}
                    @if($documentUploadMode === 'admin_only')
                        <label class="individual-toggle" :class="''" wire:click="$toggle('isIndividual')">
                            <span class="individual-toggle-check {{ $isIndividual ? 'is-checked' : '' }}">
                                <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3.5" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6L9 17l-5-5"/></svg>
                            </span>
                            <span class="min-w-0">
                                <span class="block text-sm font-medium" style="color: var(--color-primary-2);">Documento individual</span>
                                <span class="block text-xs mt-0.5" style="color: var(--color-secondary);">El administrador podrá subir un archivo distinto para cada estudiante.</span>
                            </span>
                        </label>
                    @endif

                    {{-- Archivos (si no es user_only y no es admin_only+individual) --}}
                    @if(!$documentUploadMode || $documentUploadMode !== 'user_only')
                        @if(!($documentUploadMode === 'admin_only' && $isIndividual))

                            {{-- Word --}}
                            <div class="app-field">
                                <label class="app-field-label">Archivo del documento (Word)</label>

                                <input type="file" wire:model="documentFile" accept=".doc,.docx" class="hidden" id="documentFile">
                                <label for="documentFile" class="file-dropzone">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
                                    <span>Haz clic para seleccionar</span>
                                </label>

                                @if($documentFile)
                                    <div class="file-chip">
                                        <span class="truncate">{{ $documentFile->getClientOriginalName() }}</span>
                                        <button wire:click="$set('documentFile', null)" type="button" class="file-chip-remove">
                                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" d="M6 18L18 6M6 6l12 12"/></svg>
                                        </button>
                                    </div>
                                @elseif($editingDocumentId && $period->files->find($editingDocumentId)?->name_file && !$removeDocumentFile)
                                    <div class="file-chip">
                                        <span class="truncate">Archivo actual: {{ $period->files->find($editingDocumentId)->name_file }}</span>
                                        <button wire:click="removeExistingDocumentFile" type="button" class="file-chip-remove">
                                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" d="M6 18L18 6M6 6l12 12"/></svg>
                                        </button>
                                    </div>
                                @elseif($editingDocumentId && $removeDocumentFile)
                                    <div class="file-chip file-chip--danger">
                                        <span>El archivo será eliminado al guardar</span>
                                        <button wire:click="cancelRemoveDocumentFile" type="button" class="text-xs underline">Cancelar</button>
                                    </div>
                                @endif
                                @error('documentFile') <p class="app-field-error">{{ $message }}</p> @enderror
                            </div>

                            {{-- PDF ejemplo --}}
                            <div class="app-field">
                                <label class="app-field-label">Ejemplo (PDF) <span class="font-normal" style="color: var(--color-secondary);">— referencia para estudiantes</span></label>

                                <input type="file" wire:model="documentExample" accept=".pdf" class="hidden" id="documentExample">
                                <label for="documentExample" class="file-dropzone">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
                                    <span>Haz clic para seleccionar</span>
                                </label>

                                @if($documentExample)
                                    <div class="file-chip">
                                        <span class="truncate">{{ $documentExample->getClientOriginalName() }}</span>
                                        <button wire:click="$set('documentExample', null)" type="button" class="file-chip-remove">
                                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" d="M6 18L18 6M6 6l12 12"/></svg>
                                        </button>
                                    </div>
                                @elseif($editingDocumentId && $period->files->find($editingDocumentId)?->example_name_file && !$removeExampleFile)
                                    <div class="file-chip">
                                        <span class="truncate">Archivo actual: {{ $period->files->find($editingDocumentId)->example_name_file }}</span>
                                        <button wire:click="removeExistingExampleFile" type="button" class="file-chip-remove">
                                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" d="M6 18L18 6M6 6l12 12"/></svg>
                                        </button>
                                    </div>
                                @elseif($editingDocumentId && $removeExampleFile)
                                    <div class="file-chip file-chip--danger">
                                        <span>El archivo de ejemplo será eliminado al guardar</span>
                                        <button wire:click="cancelRemoveExampleFile" type="button" class="text-xs underline">Cancelar</button>
                                    </div>
                                @endif
                            </div>

                        @endif
                    @endif

                    {{-- Firman --}}
                    <div class="app-field">
                        <label class="app-field-label">Firman</label>
                        <textarea wire:model.defer="documentFirman" rows="3" placeholder="Escribe aquí..."
                                  class="app-input w-full" style="height: auto; border-radius: 16px; padding-top: 10px; padding-bottom: 10px;"></textarea>
                        @error('documentFirman') <p class="app-field-error">{{ $message }}</p> @enderror
                    </div>

                    {{-- Observaciones --}}
                    <div class="app-field">
                        <label class="app-field-label">Observaciones</label>
                        <textarea wire:model.defer="documentObservations" rows="3" placeholder="Escribe aquí..."
                                  class="app-input w-full" style="height: auto; border-radius: 16px; padding-top: 10px; padding-bottom: 10px;"></textarea>
                        @error('documentObservations') <p class="app-field-error">{{ $message }}</p> @enderror
                    </div>

                </div>
            </div>

            {{-- ── Footer ── --}}
            <div class="flex items-center justify-between gap-2 px-6 py-4 flex-shrink-0"
                style="border-top: 1px solid var(--color-border);">

                <div>
                    @if($editingDocumentId)
                        <button type="button" wire:click="deleteDocument({{ $editingDocumentId }})" class="btn-danger">
                            Eliminar
                        </button>
                    @endif
                </div>

                <div class="flex items-center gap-2">
                    <button wire:click="closeDocumentModal"
                            class="px-4 py-2 rounded-full text-sm transition-colors duration-150 text-[var(--color-secondary)] hover:text-[var(--color-primary-2)] hover:bg-[var(--sidebar-color-hover)]">
                        Cancelar
                    </button>

                    <button wire:click="{{ $editingDocumentId ? 'saveDocument' : 'createDocument' }}"
                            wire:loading.attr="disabled"
                            class="btn-primary disabled:opacity-60 disabled:cursor-not-allowed">
                        <span wire:loading.remove wire:target="{{ $editingDocumentId ? 'saveDocument' : 'createDocument' }}">
                            {{ $editingDocumentId ? 'Guardar' : 'Crear documento' }}
                        </span>
                        <span wire:loading wire:target="{{ $editingDocumentId ? 'saveDocument' : 'createDocument' }}">
                            Procesando...
                        </span>
                    </button>
                </div>
            </div>

        </div>
    </flux:modal>
@endif
