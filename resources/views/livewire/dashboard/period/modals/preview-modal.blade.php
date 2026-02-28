{{-- =================== MODAL VISTA PREVIA =================== --}}
@if($previewPath)
    <flux:modal
        wire:model="previewPath"
        :dismissible="false"
        class="w-[95vw] sm:w-[85vw] md:w-[75vw] lg:w-[860px] xl:w-[960px] max-w-[95vw]">

        <div class="flex flex-col" style="height: 75vh; max-height: 85vh;">
            @php $ext = strtolower(pathinfo($previewPath, PATHINFO_EXTENSION)); @endphp

            {{-- ── Header ── --}}
            <div class="flex items-center justify-between px-4 sm:px-6 py-3 sm:py-4 flex-shrink-0"
                style="border-bottom: 1px solid var(--period-detail-border); background-color: var(--period-detail-card-bg);">

                <div class="flex items-center gap-3 min-w-0">
                    {{-- Icono tipo ── --}}
                    <div class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0"
                        style="background-color: var(--period-detail-bg);">
                        <svg class="w-4 h-4" style="color: var(--period-detail-accent);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                            style="color: var(--period-detail-text-primary);">
                            {{ $previewName }}
                        </h3>
                        <p class="text-xs mt-0.5" style="color: var(--period-detail-text-secondary);">
                            {{ strtoupper($ext) }} · Vista previa
                        </p>
                    </div>
                </div>
            </div>

            {{-- ── Contenido ── --}}
            <div class="flex-1 overflow-hidden"
                style="background-color: var(--period-detail-card-bg);">

                @if($ext === 'pdf')
                    <iframe
                        src="{{ asset($previewPath) }}"
                        class="w-full h-full"
                        style="background-color: var(--period-detail-bg);"
                        title="Vista previa PDF">
                    </iframe>

                @else
                    {{-- Formato no previsualizable ── --}}
                    <div class="w-full h-full flex flex-col items-center justify-center p-6 sm:p-8">
                        <div class="max-w-xs text-center space-y-4">

                            <div class="inline-flex items-center justify-center w-16 h-16 sm:w-20 sm:h-20 rounded-2xl"
                                style="background-color: var(--period-detail-bg);">
                                <svg class="w-8 h-8 sm:w-10 sm:h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                    style="color: var(--period-detail-text-secondary);">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                            </div>

                            <div class="space-y-1.5">
                                <h4 class="text-sm font-semibold"
                                    style="color: var(--period-detail-text-primary);">
                                    Vista previa no disponible
                                </h4>
                                <p class="text-xs sm:text-sm" style="color: var(--period-detail-text-secondary);">
                                    Este formato no puede visualizarse en el navegador
                                </p>
                            </div>

                            <a href="{{ asset($previewPath) }}" download="{{ $previewName }}"
                                class="inline-flex items-center justify-center gap-2 px-4 py-2 text-sm font-semibold rounded-lg shadow-sm transition-all duration-200 hover:-translate-y-0.5 hover:shadow-lg"
                                style="background: linear-gradient(135deg, var(--period-detail-brand-primary) 0%, var(--period-detail-brand-secondary) 100%);
                                       color: var(--period-detail-text-icon);">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                </svg>
                                Descargar archivo
                            </a>
                        </div>
                    </div>
                @endif
            </div>

            {{-- ── Footer (solo PDF) ── --}}
            @if($ext === 'pdf')
                <div class="flex items-center gap-3 px-4 sm:px-6 py-3 flex-shrink-0"
                    style="border-top: 1px solid var(--period-detail-border); background-color: var(--period-detail-card-bg);">

                    <p class="text-xs flex-1 hidden sm:block" style="color: var(--period-detail-text-secondary);">
                        Usa los controles del visor para navegar
                    </p>

                    <div class="flex items-center gap-2 ml-auto">
                        <a href="{{ asset($previewPath) }}" download="{{ $previewName }}"
                            class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold rounded-lg shadow-sm transition-all duration-200 hover:-translate-y-0.5 hover:shadow-lg"
                            style="background: linear-gradient(135deg, var(--period-detail-brand-primary) 0%, var(--period-detail-brand-secondary) 100%);
                                   color: var(--period-detail-text-icon);">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                            </svg>
                            Descargar
                        </a>
                    </div>
                </div>
            @endif

        </div>
    </flux:modal>
@endif