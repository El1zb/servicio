{{-- =================== MODAL SUBIR DOCUMENTO =================== --}}
@if($isUploadModalOpen && $uploadDocId)
    @php
        $uploadDocument = $this->uploadDocument;
        $uploadFile     = $uploadDocument?->file;
        $selectedFile   = $fileUpload[$uploadDocId] ?? null;
    @endphp

    @if($uploadDocument && $uploadFile)
        <flux:modal
            wire:model="isUploadModalOpen"
            :dismissible="false"
            :closable="false"
            class="w-[95vw] sm:w-[90vw] lg:w-[560px] max-w-[95vw]"
            style="border-color: var(--color-border);">

            <div class="flex flex-col">

                {{-- ── Header ── --}}
                <div class="flex items-center justify-between gap-4 px-6 py-5"
                    style="border-bottom: 1px solid var(--color-border);">
                    <div class="min-w-0">
                        <h2 class="text-lg font-semibold leading-tight truncate" style="color: var(--color-primary-2);">
                            Subir documento
                        </h2>
                        <p class="text-sm mt-0.5 truncate" style="color: var(--color-secondary);">
                            {{ $uploadDocument->name }}
                        </p>
                    </div>
                </div>

                {{-- ── Body ──
                     Mismo patrón que documentFile/documentExample en
                     document-modal.blade.php (admin): <label for> + <input
                     type="file"> oculto, sin envoltura Alpine. Un dropzone
                     con x-data/@click/@drop alrededor del input, dentro de
                     este flux:modal, provocaba que el modal se cerrara solo
                     al seleccionar el archivo — este patrón ya probado no
                     tiene ese problema. --}}
                <div class="px-6 py-5" style="background-color: var(--color-modal-bg);">

                    <input type="file" wire:model="fileUpload.{{ $uploadDocId }}" accept=".pdf" class="hidden" id="uploadFileInput">
                    <label for="uploadFileInput" class="file-dropzone" style="padding: 28px 16px;">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="color: var(--color-icon);"><path d="M7 18a4.6 4.4 0 0 1 0-9 5 4.5 0 0 1 11 2h1a3.5 3.5 0 0 1 0 7h-1"/><path d="M12 12v9"/><path d="M9 15l3-3 3 3"/></svg>
                        <span>Haz clic para seleccionar tu archivo</span>
                        <span class="text-xs" style="opacity: 0.8;">
                            Formato soportado: PDF
                            @if($uploadFile->max_size)
                                · Tamaño máximo: {{ $this->formatSize($uploadFile->max_size * 1024) }}
                            @endif
                        </span>
                    </label>

                    @error('fileUpload.' . $uploadDocId) <p class="app-field-error mt-2">{{ $message }}</p> @enderror

                    <div wire:loading wire:target="fileUpload.{{ $uploadDocId }}" class="mt-3 text-xs text-center" style="color: var(--color-secondary);">
                        Cargando archivo...
                    </div>

                    @if($selectedFile)
                        <div wire:loading.remove wire:target="fileUpload.{{ $uploadDocId }}" class="file-chip">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="flex-shrink-0"><path d="M13 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9z"/><path d="M13 2v7h7"/></svg>
                            <span class="truncate flex-1">{{ $selectedFile->getClientOriginalName() }} · {{ $this->formatSize($selectedFile->getSize()) }}</span>
                            <button wire:click="removeSelectedUpload" type="button" class="file-chip-remove">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                        </div>
                    @endif
                </div>

                {{-- ── Footer ── --}}
                <div class="flex items-center justify-end gap-2 px-6 py-4"
                    style="border-top: 1px solid var(--color-border);">

                    <button wire:click="closeUploadModal"
                            class="px-4 py-2 rounded-full text-sm transition-colors duration-150 text-[var(--color-secondary)] hover:text-[var(--color-primary-2)] hover:bg-[var(--sidebar-color-hover)]">
                        Cancelar
                    </button>

                    <button wire:click="confirmUpload"
                            wire:loading.attr="disabled"
                            wire:target="confirmUpload"
                            @disabled(! $selectedFile)
                            class="btn-primary disabled:opacity-60 disabled:cursor-not-allowed">
                        <span wire:loading.remove wire:target="confirmUpload">Subir</span>
                        <span wire:loading wire:target="confirmUpload">Subiendo...</span>
                    </button>
                </div>

            </div>
        </flux:modal>
    @endif
@endif
