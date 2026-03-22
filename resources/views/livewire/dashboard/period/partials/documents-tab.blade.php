{{-- DOCUMENTOS BASE --}}
<div class="space-y-4 sm:space-y-6">

    {{-- ================== CREAR / EDITAR DOCUMENTO ================== --}}
    <div class="backdrop-blur-xl rounded-xl sm:rounded-2xl shadow-xl sm:shadow-2xl overflow-hidden"
         style="background-color: var(--color-card-bg);">

        {{-- Header del formulario --}}
        <div class="p-4 sm:p-6"
            style="background-color: var(--color-icon-bg);
                   border-bottom: 1px solid var(--color-border-hover);">
            <div class="flex items-center gap-3">
                <div class="flex-1 min-w-0">
                    <h2 class="text-base sm:text-xl font-bold truncate" style="color: var(--color-primary-2);">
                        {{ $editingDocumentId ? 'Editar Documento Base' : 'Crear Nuevo Documento Base' }}
                    </h2>
                    <p class="text-xs sm:text-sm mt-0.5 truncate" style="color: var(--color-secondary);">
                        {{ $editingDocumentId ? 'Actualiza la información del documento' : 'Se le asignará este documento a los estudiantes' }}
                    </p>
                </div>
            </div>
        </div>

        {{-- Formulario --}}
        <div class="p-4 sm:p-6">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6">

                {{-- Nombre del Documento --}}
                <flux:field class="lg:col-span-2">
                    <flux:label class="flex items-center">
                        <svg class="w-4 h-4 inline-block mr-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            style="color: var(--color-primary);">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                        </svg>
                        <span>Nombre del Documento</span>
                        <span class="ml-1" style="color: rgb(248,113,113);">*</span>
                    </flux:label>
                    <flux:input wire:model.defer="documentName" type="text"
                        placeholder="Ej: Anexo 10. Plan de Trabajo"
                        style="background-color: var(--color-icon-bg); color: var(--color-primary-2);"
                        class="w-full px-3 sm:px-4 py-2.5 sm:py-3 rounded-lg sm:rounded-xl transition-all"/>
                    <flux:error name="documentName" />
                </flux:field>

                {{-- Modo de Carga --}}
                <div class="lg:col-span-2">
                    <label class="block text-sm font-semibold mb-3 flex items-center gap-2"
                        style="color: var(--color-primary-2);">
                        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            style="color: var(--color-primary);">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                        </svg>
                        Modo de Carga
                        <span class="ml-1" style="color: rgb(248,113,113);">*</span>
                    </label>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                        @foreach([
                            'bidirectional' => ['label' => 'Bidireccional', 'desc' => 'Admin y estudiantes pueden subir archivos', 'icon' => 'M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4'],
                            'user_only'     => ['label' => 'Solo Estudiantes', 'desc' => 'Solo estudiantes pueden subir archivos', 'icon' => 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z'],
                            'admin_only'    => ['label' => 'Solo Admin', 'desc' => 'Solo administradores pueden subir archivos', 'icon' => 'M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z'],
                        ] as $modeValue => $modeData)
                            <label class="relative cursor-pointer group">
                                <input type="radio"
                                    wire:model.live="documentUploadMode"
                                    value="{{ $modeValue }}"
                                    class="peer sr-only">
                                <div class="p-3 sm:p-4 rounded-lg sm:rounded-xl border-2 transition-all flex h-full peer-checked:border-2"
                                    style="background-color: var(--color-icon-bg); border-color: var(--color-border-hover);"
                                    onmouseover="if(!this.previousElementSibling.checked) this.style.borderColor='var(--color-primary)'"
                                    onmouseout="if(!this.previousElementSibling.checked) this.style.borderColor='var(--color-border-hover)'">
                                    <div class="flex items-center sm:items-stretch gap-3 w-full">
                                        <div class="upload-mode-icon w-8 h-8 sm:w-10 sm:h-10 rounded-lg flex items-center justify-center flex-shrink-0"
                                            style="background-color: var(--color-card-bg);">
                                            <svg class="w-4 h-4 sm:w-5 sm:h-5 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                                style="color: var(--color-secondary);">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $modeData['icon'] }}"/>
                                            </svg>
                                        </div>
                                        <div class="flex-1 flex flex-col justify-between min-w-0">
                                            <div>
                                                <h4 class="font-semibold text-xs sm:text-sm mb-0.5 sm:mb-1 leading-tight" style="color: var(--color-primary-2);">
                                                    {{ $modeData['label'] }}
                                                </h4>
                                                <p class="text-xs leading-tight" style="color: var(--color-secondary);">
                                                    {{ $modeData['desc'] }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </label>
                        @endforeach
                    </div>
                    <flux:error name="documentUploadMode" />
                </div>

                <style>
                    input[type="radio"]:checked + div {
                        border-color: var(--color-primary) !important;
                        background-color: var(--color-icon-bg);
                    }
                    input[type="radio"]:checked + div .upload-mode-icon {
                        background-color: var(--color-primary) !important;
                    }
                    input[type="radio"]:checked + div .upload-mode-icon svg {
                        color: var(--color-bg) !important;
                    }
                </style>

                {{-- Fecha Límite y Tamaño (solo si no es admin_only) --}}
                @if($documentUploadMode !== 'admin_only')
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-5 lg:col-span-2">
                        <flux:field>
                            <flux:label class="flex items-center">
                                <svg class="w-4 h-4 inline-block mr-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                    style="color: var(--color-primary);">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                <span>Fecha Límite</span>
                                <span class="ml-1" style="color: rgb(248,113,113);">*</span>
                            </flux:label>
                            <flux:input wire:model.defer="documentDeadline" type="date"
                                min="{{ $period->start_date }}" max="{{ $period->end_date }}"
                                style="background-color: var(--color-icon-bg); color: var(--color-primary-2);"
                                class="w-full px-3 sm:px-4 py-2.5 sm:py-3 rounded-lg sm:rounded-xl transition-all"/>
                            <flux:error name="documentDeadline" />
                        </flux:field>

                        <flux:field>
                            <flux:label class="flex items-center">
                                <svg class="w-4 h-4 inline-block mr-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                    style="color: var(--color-primary);">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4"/>
                                </svg>
                                <span>Tamaño Máximo (KB)</span>
                                <span class="ml-1" style="color: rgb(248,113,113);">*</span>
                            </flux:label>
                            <flux:input wire:model.defer="maxSize" type="number" placeholder="10240"
                                min="1" max="20480"
                                style="background-color: var(--color-icon-bg); color: var(--color-primary-2);"
                                class="w-full px-3 sm:px-4 py-2.5 sm:py-3 rounded-lg sm:rounded-xl transition-all"/>
                            <flux:error name="maxSize" />
                        </flux:field>
                    </div>
                @endif

                {{-- Checkbox Individual (solo si admin_only) --}}
                @if($documentUploadMode === 'admin_only')
                    <div class="lg:col-span-2">
                        <div class="p-4 sm:p-5 rounded-lg sm:rounded-xl border-2 transition-all cursor-pointer"
                            style="background-color: var(--color-icon-bg); border-color: {{ $isIndividual ? 'var(--color-primary)' : 'var(--color-border-hover)' }};"
                            wire:click="$toggle('isIndividual')"
                            onmouseover="this.style.borderColor='var(--color-primary)'"
                            onmouseout="this.style.borderColor='{{ $isIndividual ? 'var(--color-primary)' : 'var(--color-border-hover)' }}'">
                            <div class="flex items-start gap-3 sm:gap-4">
                                <div class="flex-shrink-0 pt-0.5">
                                    <input type="checkbox" wire:model.live="isIndividual" id="isIndividual" class="sr-only">
                                    <label for="isIndividual"
                                        class="w-5 h-5 sm:w-6 sm:h-6 rounded-md border-2 flex items-center justify-center cursor-pointer transition-all"
                                        style="{{ $isIndividual
                                            ? 'background-color: var(--color-primary); border-color: var(--color-primary);'
                                            : 'background-color: var(--color-card-bg); border-color: var(--color-border-hover);' }}">
                                        @if($isIndividual)
                                            <svg class="w-3 h-3 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                                style="color: var(--color-bg);">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                                            </svg>
                                        @endif
                                    </label>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center gap-2 mb-1 sm:mb-2">
                                        <svg class="w-4 h-4 sm:w-5 sm:h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                            style="color: var(--color-primary);">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                        </svg>
                                        <h4 class="text-sm sm:text-base font-medium" style="color: var(--color-primary-2);">Documento Individual</h4>
                                    </div>
                                    <p class="text-xs sm:text-sm leading-relaxed" style="color: var(--color-secondary);">
                                        El administrador podrá subir un archivo específico diferente para cada alumno.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Archivos (Word y PDF) --}}
                @if(!$documentUploadMode || $documentUploadMode !== 'user_only')
                    @if(!($documentUploadMode === 'admin_only' && $isIndividual))

                        {{-- Archivo Word --}}
                        <flux:field class="lg:col-span-2">
                            <flux:label class="flex items-center">
                                <svg class="w-4 h-4 inline-block mr-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                    style="color: var(--color-primary);">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                <span>Archivo del Documento (Word)</span>
                            </flux:label>

                            <input type="file" wire:model="documentFile" accept=".doc,.docx" class="hidden" id="documentFile">
                            <label for="documentFile"
                                class="group flex items-center justify-center w-full px-4 sm:px-6 py-6 sm:py-8 border-2 border-dashed rounded-lg sm:rounded-xl cursor-pointer transition-all"
                                style="background-color: var(--color-icon-bg); border-color: var(--color-border-hover); color: var(--color-primary-2);"
                                onmouseover="this.style.borderColor='var(--color-primary)';"
                                onmouseout="this.style.borderColor='var(--color-border-hover)';">
                                <div class="text-center">
                                    <svg class="w-8 h-8 sm:w-12 sm:h-12 mx-auto mb-2 sm:mb-3 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                        style="color: var(--color-secondary);">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                                    </svg>
                                    <p class="text-xs sm:text-sm font-medium transition-colors" style="color: var(--color-secondary);">
                                        Haz clic para seleccionar
                                    </p>
                                </div>
                            </label>

                            @if($documentFile)
                                <div class="mt-3 p-3 rounded-lg flex items-center gap-3"
                                    style="background-color: var(--color-icon-bg); border: 1px solid var(--color-border-hover);">
                                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                        style="color: var(--color-secondary);">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                                    </svg>
                                    <span class="text-xs sm:text-sm flex-1 truncate" style="color: var(--color-secondary);">{{ $documentFile->getClientOriginalName() }}</span>
                                    <button wire:click="$set('documentFile', null)" type="button"
                                        class="flex-shrink-0"
                                        style="color: var(--color-secondary);">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                    </button>
                                </div>
                            @elseif($editingDocumentId && $period->files->find($editingDocumentId)?->name_file && !$removeDocumentFile)
                                <div class="mt-3 p-3 rounded-lg flex items-center gap-3"
                                    style="background-color: var(--color-icon-bg); border: 1px solid var(--color-border-hover);">
                                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                        style="color: var(--color-secondary);">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                    <span class="text-xs sm:text-sm flex-1 truncate" style="color: var(--color-secondary);">
                                        Archivo actual: {{ $period->files->find($editingDocumentId)->name_file }}
                                    </span>
                                    <button wire:click="removeExistingDocumentFile" type="button"
                                        class="flex-shrink-0"
                                        style="color: var(--color-secondary);">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                    </button>
                                </div>
                            @elseif($editingDocumentId && $removeDocumentFile)
                                <div class="mt-3 p-3 rounded-lg flex items-center gap-3"
                                    style="background-color: rgba(239, 68, 68, 0.1); border: 1px solid rgba(239, 68, 68, 0.3);">
                                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color: rgb(239, 68, 68);">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                    </svg>
                                    <span class="text-xs sm:text-sm flex-1" style="color: rgb(239, 68, 68);">El archivo será eliminado al guardar</span>
                                    <button wire:click="cancelRemoveDocumentFile" type="button"
                                        class="px-2 sm:px-3 py-1 text-xs rounded-lg flex-shrink-0"
                                        style="color: var(--color-primary-2);">Cancelar</button>
                                </div>
                            @endif
                            <flux:error name="documentFile" />
                        </flux:field>

                        {{-- Archivo PDF de ejemplo --}}
                        <div class="lg:col-span-2">
                            <label class="block text-sm font-semibold mb-3 flex items-center gap-2"
                                style="color: var(--color-primary-2);">
                                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                    style="color: var(--color-primary);">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                </svg>
                                Ejemplo (PDF)
                                <span class="text-xs font-normal hidden sm:inline" style="color: var(--color-secondary);">
                                    — Archivo de referencia para estudiantes
                                </span>
                            </label>

                            <input type="file" wire:model="documentExample" accept=".pdf" class="hidden" id="documentExample">
                            <label for="documentExample"
                                class="group flex items-center justify-center w-full px-4 sm:px-6 py-5 sm:py-6 border-2 border-dashed rounded-lg sm:rounded-xl cursor-pointer transition-all"
                                style="background-color: var(--color-icon-bg); border-color: var(--color-border-hover);"
                                onmouseover="this.style.borderColor='var(--color-primary)';"
                                onmouseout="this.style.borderColor='var(--color-border-hover)';">
                                <div class="text-center">
                                    <svg class="w-8 h-8 sm:w-10 sm:h-10 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                        style="color: var(--color-secondary);">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                    </svg>
                                    <p class="text-xs sm:text-sm font-medium" style="color: var(--color-secondary);">Subir ejemplo</p>
                                </div>
                            </label>

                            @if($documentExample)
                                <div class="mt-3 p-3 rounded-lg flex items-center gap-3"
                                    style="background-color: var(--color-icon-bg); border: 1px solid var(--color-border-hover);">
                                    <span class="text-xs sm:text-sm flex-1 truncate" style="color: var(--color-secondary);">{{ $documentExample->getClientOriginalName() }}</span>
                                    <button wire:click="$set('documentExample', null)" type="button"
                                        class="flex-shrink-0"
                                        style="color: var(--color-secondary);">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                    </button>
                                </div>
                            @elseif($editingDocumentId && $period->files->find($editingDocumentId)?->example_name_file && !$removeExampleFile)
                                <div class="mt-3 p-3 rounded-lg flex items-center gap-3"
                                    style="background-color: var(--color-icon-bg); border: 1px solid var(--color-border-hover);">
                                    <span class="text-xs sm:text-sm flex-1 truncate" style="color: var(--color-secondary);">
                                        Archivo actual: {{ $period->files->find($editingDocumentId)->example_name_file }}
                                    </span>
                                    <button wire:click="removeExistingExampleFile" type="button"
                                        class="flex-shrink-0"
                                        style="color: var(--color-secondary);">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                    </button>
                                </div>
                            @elseif($editingDocumentId && $removeExampleFile)
                                <div class="mt-3 p-3 rounded-lg flex items-center gap-3"
                                    style="background-color: rgba(239, 68, 68, 0.1); border: 1px solid rgba(239, 68, 68, 0.3);">
                                    <span class="text-xs sm:text-sm flex-1" style="color: rgb(239, 68, 68);">El archivo de ejemplo será eliminado al guardar</span>
                                    <button wire:click="cancelRemoveExampleFile" type="button"
                                        class="px-2 sm:px-3 py-1 text-xs rounded-lg flex-shrink-0"
                                        style="color: var(--color-primary-2);">Cancelar</button>
                                </div>
                            @endif
                        </div>

                    @endif
                @endif

                {{-- Firman --}}
                <flux:field class="lg:col-span-2">
                    <flux:label class="flex items-center">
                        <svg class="w-4 h-4 inline-block mr-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            style="color: var(--color-primary);">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        <span>Firman</span>
                    </flux:label>
                    <flux:textarea wire:model.defer="documentFirman" rows="3"
                        placeholder="Escribe aquí..."
                        style="background-color: var(--color-icon-bg); color: var(--color-primary-2);"
                        class="w-full px-3 sm:px-4 py-2.5 sm:py-3 rounded-lg sm:rounded-xl transition-all resize-none"/>
                    <flux:error name="documentFirman" />
                </flux:field>

                {{-- Observaciones --}}
                <flux:field class="lg:col-span-2">
                    <flux:label class="flex items-center">
                        <svg class="w-4 h-4 inline-block mr-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            style="color: var(--color-primary);">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <span>Observaciones</span>
                    </flux:label>
                    <flux:textarea wire:model.defer="documentObservations" rows="4"
                        placeholder="Escribe aquí..."
                        style="background-color: var(--color-icon-bg); color: var(--color-primary-2);"
                        class="w-full px-3 sm:px-4 py-2.5 sm:py-3 rounded-lg sm:rounded-xl transition-all resize-none"/>
                    <flux:error name="documentObservations" />
                </flux:field>

            </div>

            {{-- Botones de acción --}}
            <div class="flex flex-col-reverse sm:flex-row sm:justify-end gap-2 sm:gap-3 mt-6 sm:mt-8 pt-4 sm:pt-6"
                style="border-top: 1px solid var(--color-border-hover);">
                @if($editingDocumentId)
                    <button wire:click="cancelEditDocument"
                        class="w-full sm:w-auto px-4 py-2 rounded-lg text-sm transition-all duration-200 hover:-translate-y-0.5 text-center"
                        style="color: var(--color-secondary); background-color: transparent;"
                        onmouseover="this.style.color='var(--color-primary-2)'; this.style.backgroundColor='var(--color-border-hover)'"
                        onmouseout="this.style.color='var(--color-secondary)'; this.style.backgroundColor='transparent'">
                        Cancelar
                    </button>
                @endif

                <button wire:click="{{ $editingDocumentId ? 'saveDocument' : 'createDocument' }}"
                    wire:loading.attr="disabled"
                    class="w-full sm:w-auto px-4 py-2 text-sm font-semibold rounded-lg shadow-sm transition-all disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center gap-2"
                    style="background-color: var(--color-primary); color: var(--color-bg);"
                    onmouseover="this.style.backgroundColor='var(--color-primary-2)'"
                    onmouseout="this.style.backgroundColor='var(--color-primary)'">
                    <span wire:loading.remove wire:target="{{ $editingDocumentId ? 'saveDocument' : 'createDocument' }}">
                        {{ $editingDocumentId ? 'Guardar' : 'Crear Documento' }}
                    </span>
                    <span wire:loading wire:target="{{ $editingDocumentId ? 'saveDocument' : 'createDocument' }}" class="flex items-center gap-2">
                        <svg class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Procesando...
                    </span>
                </button>
            </div>
        </div>
    </div>

    {{-- ================== LISTA DE DOCUMENTOS ================== --}}
    @if(!$editingDocumentId)
        <div class="backdrop-blur-xl rounded-xl sm:rounded-2xl shadow-xl sm:shadow-2xl overflow-hidden"
             style="background-color: var(--color-card-bg);">

            {{-- Header lista --}}
            <div class="p-4 sm:p-6"
                style="background-color: var(--color-icon-bg);
                       border-bottom: 1px solid var(--color-border-hover);">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                    <div>
                        <h2 class="text-base sm:text-xl font-bold flex items-center gap-2" style="color: var(--color-primary-2);">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                style="color: var(--color-primary);">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            Documentos Base Creados
                        </h2>
                        <p class="text-xs sm:text-sm mt-0.5" style="color: var(--color-secondary);">
                            {{ $period->files->count() }} {{ $period->files->count() === 1 ? 'documento disponible' : 'documentos disponibles' }}
                        </p>
                    </div>

                    {{-- Buscador --}}
                    <div class="w-full sm:max-w-xs sm:ml-auto">
                        <div class="relative w-full">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                    style="color: var(--color-secondary);">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                            </div>
                            <input type="text"
                                wire:model.live.debounce.300ms="searchDocuments"
                                placeholder="Buscar documento..."
                                class="w-full pl-9 pr-4 py-2 rounded-lg text-sm transition-all focus:ring-2 focus:outline-none"
                                style="background-color: var(--color-card-bg);
                                       color: var(--color-primary-2);
                                       border: 1px solid var(--color-border-hover);">
                        </div>
                    </div>
                </div>
            </div>

            {{-- Items --}}
            <div>
                @forelse($paginatedFiles as $file)
                    <div class="p-4 sm:p-6 transition-all"
                         style="border-bottom: 1px solid var(--color-border-hover);"
                         onmouseover="this.style.backgroundColor='transparent'"
                         onmouseout="this.style.backgroundColor='transparent'">

                        <div class="flex items-start justify-between gap-3">
                            {{-- Info --}}
                            <div class="flex items-start gap-3 flex-1 min-w-0">
                                <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-lg flex items-center justify-center flex-shrink-0"
                                    style="background-color: var(--color-icon-bg);">
                                    <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                        style="color: var(--color-secondary);">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h3 class="text-sm sm:text-base font-semibold truncate" style="color: var(--color-primary-2);">
                                        {{ $file->name }}
                                    </h3>
                                    <div class="flex flex-wrap items-center gap-x-3 gap-y-1 mt-1 text-xs sm:text-sm"
                                         style="color: var(--color-secondary);">
                                        <span class="flex items-center gap-1">
                                            <svg class="w-3 h-3 sm:w-4 sm:h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                            </svg>
                                            {{ $file->created_at->format('d/m/Y') }}
                                        </span>
                                        @if($file->limit_date)
                                            <span class="flex items-center gap-1">
                                                <svg class="w-3 h-3 sm:w-4 sm:h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                </svg>
                                                Límite: {{ \Carbon\Carbon::parse($file->limit_date)->format('d/m/Y') }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            {{-- Acciones --}}
                            <div class="flex gap-1 items-center flex-shrink-0">
                                @if($file->file_path)
                                    <div class="relative group/word">
                                        <button wire:click="previewFile('{{ $file->file_path }}','{{ $file->name_file }}')"
                                            class="w-7 h-7 sm:w-8 sm:h-8 flex items-center justify-center rounded-lg transition-all duration-200 cursor-pointer text-xs hover:-translate-y-0.5"
                                            style="background-color: var(--color-icon-bg); color: var(--color-secondary);"
                                            onmouseover="this.style.backgroundColor='var(--color-border-hover)'"
                                            onmouseout="this.style.backgroundColor='var(--color-icon-bg)'">
                                            <i class="fas fa-file-word"></i>
                                        </button>
                                        <div class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 px-2 py-1 text-xs font-medium rounded-lg opacity-0 group-hover/word:opacity-100 transition-all duration-200 pointer-events-none whitespace-nowrap z-50"
                                            style="background-color: var(--color-icon-bg); color: var(--color-secondary); border: 1px solid var(--color-border-hover);">
                                            Ver Word
                                            <div class="absolute top-full left-1/2 -translate-x-1/2 border-4 border-transparent"
                                                style="border-top-color: var(--color-icon-bg);"></div>
                                        </div>
                                    </div>
                                @endif

                                @if($file->example_path)
                                    <div class="relative group/pdf">
                                        <button wire:click="previewFile('{{ $file->example_path }}','{{ $file->example_name_file }}')"
                                            class="w-7 h-7 sm:w-8 sm:h-8 flex items-center justify-center rounded-lg transition-all duration-200 cursor-pointer text-xs hover:-translate-y-0.5"
                                            style="background-color: var(--color-icon-bg); color: var(--color-secondary);"
                                            onmouseover="this.style.backgroundColor='var(--color-border-hover)'"
                                            onmouseout="this.style.backgroundColor='var(--color-icon-bg)'">
                                            <i class="fas fa-file-pdf"></i>
                                        </button>
                                        <div class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 px-2 py-1 text-xs font-medium rounded-lg opacity-0 group-hover/pdf:opacity-100 transition-all duration-200 pointer-events-none whitespace-nowrap z-50"
                                            style="background-color: var(--color-icon-bg); color: var(--color-secondary); border: 1px solid var(--color-border-hover);">
                                            Ver PDF
                                            <div class="absolute top-full left-1/2 -translate-x-1/2 border-4 border-transparent"
                                                style="border-top-color: var(--color-icon-bg);"></div>
                                        </div>
                                    </div>
                                @endif

                                <div class="relative group/edit">
                                    <button wire:click="editDocument({{ $file->id }})"
                                        class="w-7 h-7 sm:w-8 sm:h-8 flex items-center justify-center rounded-lg transition-all duration-200 cursor-pointer text-xs hover:-translate-y-0.5"
                                        style="background-color: var(--color-icon-bg); color: var(--color-secondary);"
                                        onmouseover="this.style.backgroundColor='var(--color-border-hover)'"
                                        onmouseout="this.style.backgroundColor='var(--color-icon-bg)'">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <div class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 px-2 py-1 text-xs font-medium rounded-lg opacity-0 group-hover/edit:opacity-100 transition-all duration-200 pointer-events-none whitespace-nowrap z-50"
                                        style="background-color: var(--color-icon-bg); color: var(--color-secondary); border: 1px solid var(--color-border-hover);">
                                        Editar
                                        <div class="absolute top-full left-1/2 -translate-x-1/2 border-4 border-transparent"
                                            style="border-top-color: var(--color-icon-bg);"></div>
                                    </div>
                                </div>

                                <div class="relative group/delete">
                                    <button wire:click="deleteDocument({{ $file->id }})"
                                        class="w-7 h-7 sm:w-8 sm:h-8 flex items-center justify-center rounded-lg transition-all duration-200 cursor-pointer text-xs hover:-translate-y-0.5"
                                        style="background-color: var(--color-icon-bg); color: var(--color-secondary);"
                                        onmouseover="this.style.backgroundColor='var(--color-border-hover)'"
                                        onmouseout="this.style.backgroundColor='var(--color-icon-bg)'">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                    <div class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 px-2 py-1 text-xs font-medium rounded-lg opacity-0 group-hover/delete:opacity-100 transition-all duration-200 pointer-events-none whitespace-nowrap z-50"
                                        style="background-color: var(--color-icon-bg); color: var(--color-secondary); border: 1px solid var(--color-border-hover);">
                                        Eliminar
                                        <div class="absolute top-full left-1/2 -translate-x-1/2 border-4 border-transparent"
                                            style="border-top-color: var(--color-icon-bg);"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="p-10 sm:p-16 text-center">
                        <div class="w-16 h-16 sm:w-20 sm:h-20 mx-auto mb-4 rounded-2xl flex items-center justify-center"
                             style="background-color: var(--color-icon-bg);">
                            <svg class="w-8 h-8 sm:w-10 sm:h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                 style="color: var(--color-secondary);">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                        <p class="text-base sm:text-lg font-semibold mb-1 sm:mb-2" style="color: var(--color-secondary);">No hay documentos base</p>
                        <p class="text-xs sm:text-sm" style="color: var(--color-secondary);">Crea documentos para que los estudiantes puedan subirlos</p>
                    </div>
                @endforelse
            </div>

            <div class="p-3 sm:p-4">
                {{ $paginatedFiles->links() }}
            </div>
        </div>
    @endif

    @include('livewire.dashboard.period.modals.delete-document-modal')
    @include('livewire.dashboard.period.modals.upload-mode-modal')
    @include('livewire.dashboard.period.modals.preview-modal')

</div>