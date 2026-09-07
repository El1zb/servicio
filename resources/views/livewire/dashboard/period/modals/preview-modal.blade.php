{{-- =================== MODAL VISTA PREVIA =================== --}}
@if($previewPath)
    <flux:modal
        wire:model="previewPath"
        :dismissible="false"
        :closable="false"
        class="w-[95vw] sm:w-[85vw] md:w-[75vw] lg:w-[860px] xl:w-[960px] max-w-[95vw]">

        <div class="flex flex-col" style="height: 75vh; max-height: 85vh;">
            @php
                $ext = strtolower(pathinfo($previewPath, PATHINFO_EXTENSION));
                // El .docx se sirve convertido a PDF (LibreOffice, ver
                // DocxToPdfConverter) para poder visualizarlo aquí igual que
                // un PDF real, sin perder la extensión original mostrada arriba.
                $previewIsViewable = in_array($ext, ['pdf', 'docx']);
                $previewSrc        = route('files.show', array_filter([
                    'path' => $previewPath,
                    'as'   => $ext === 'docx' ? 'pdf' : null,
                ]));
            @endphp

            {{-- ── Header ── --}}
            <div class="flex items-center justify-between px-4 sm:px-6 py-3 sm:py-4 flex-shrink-0"
                style="border-bottom: 1px solid var(--color-border-hover); background-color: var(--color-modal-bg);">

                <div class="flex items-center gap-3 min-w-0">
                    {{-- Icono tipo ── --}}
                    <div class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0"
                        style="background-color: var(--color-icon-bg);">
                        <svg class="w-4 h-4" style="color: var(--color-secondary);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            @if($ext === 'pdf')
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                            @else
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            @endif
                        </svg>
                    </div>

                    {{-- Nombre y tipo ── --}}
                    <div class="min-w-0">
                        <h3 class="text-sm font-semibold leading-tight truncate"
                            style="color: var(--color-primary-2);">
                            {{ $previewName }}
                        </h3>
                        <p class="text-xs mt-0.5" style="color: var(--color-secondary);">
                            {{ strtoupper($ext) }} · Vista previa
                        </p>
                    </div>
                </div>
            </div>

            {{-- ── Contenido ── --}}
            <div class="flex-1 overflow-hidden"
                style="background-color: var(--color-modal-bg);">

                @if($previewIsViewable)
                    <iframe
                        src="{{ $previewSrc }}"
                        class="w-full h-full"
                        style="background-color: var(--color-icon-bg);"
                        title="Vista previa">
                    </iframe>

                @else
                    {{-- Formato no previsualizable ── --}}
                    <div class="w-full h-full flex flex-col items-center justify-center p-6 sm:p-8">
                        <div class="max-w-xs text-center space-y-4">

                            <div class="inline-flex items-center justify-center w-16 h-16 sm:w-20 sm:h-20 rounded-2xl"
                                style="background-color: var(--color-icon-bg);">
                                <svg class="w-8 h-8 sm:w-10 sm:h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                    style="color: var(--color-secondary);">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                            </div>

                            <div class="space-y-1.5">
                                <h4 class="text-sm font-semibold"
                                    style="color: var(--color-primary-2);">
                                    Vista previa no disponible
                                </h4>
                                <p class="text-xs sm:text-sm" style="color: var(--color-secondary);">
                                    Este formato no puede visualizarse en el navegador
                                </p>
                            </div>

                            <a href="{{ route('files.show', ['path' => $previewPath]) }}" download="{{ $previewName }}" class="btn-primary">
                                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                </svg>
                                Descargar archivo
                            </a>
                        </div>
                    </div>
                @endif
            </div>

            {{-- ── Footer ── --}}
            <div class="flex items-center justify-end gap-2 px-4 sm:px-6 py-3 flex-shrink-0"
                style="border-top: 1px solid var(--color-border-hover); background-color: var(--color-modal-bg);">

                <button type="button" wire:click="closePreview"
                        class="px-4 py-2 rounded-full text-sm transition-colors duration-150 text-[var(--color-secondary)] hover:text-[var(--color-primary-2)] hover:bg-[var(--sidebar-color-hover)]">
                    Cerrar
                </button>

                @if($previewIsViewable)
                    <a href="{{ route('files.show', ['path' => $previewPath]) }}" download="{{ $previewName }}" class="btn-primary">
                        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                        </svg>
                        Descargar
                    </a>
                @endif
            </div>

        </div>
    </flux:modal>
@endif