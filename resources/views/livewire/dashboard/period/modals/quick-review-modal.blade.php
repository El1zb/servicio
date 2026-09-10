{{-- =================== VISOR UNIFICADO DE REVISIÓN (ocupa toda la ventana del navegador,
     pero sin pedir pantalla completa real — sin requestFullscreen()) =================== --}}
<flux:modal
    wire:model="showQuickReviewModal"
    :dismissible="false"
    :closable="false"
    class="!p-0 !max-w-none !rounded-none !shadow-none w-screen h-screen !m-0 overflow-hidden !outline-none"
    style="outline: none; max-width: 100vw; max-height: 100dvh; margin: 0; inset: 0;">

    <div class="flex flex-col"
        style="height: 100dvh; background-color: var(--color-contenedor-view);"
        x-data="{ docsSheetOpen: false, detailSheetOpen: false }"
        @keydown.window="
            const el = document.activeElement;
            const typing = el && (el.tagName === 'INPUT' || el.tagName === 'TEXTAREA' || el.isContentEditable);
            if ($wire.showQuickReviewModal && !typing) {
                if ($event.key === 'ArrowLeft') {
                    const btn = document.querySelector('button[title=\'Documento anterior\']');
                    if (btn && !btn.disabled) btn.click();
                } else if ($event.key === 'ArrowRight') {
                    const btn = document.querySelector('button[title=\'Siguiente documento\']');
                    if (btn && !btn.disabled) btn.click();
                }
            }
        ">

        {{-- ── Barra 1: estudiante ── --}}
        <div class="flex items-center justify-between gap-4 px-4 sm:px-6 py-3 flex-shrink-0"
            style="background-color: var(--sidebar-color-bg); box-shadow: 0 1px 3px rgba(0,0,0,0.05); position: relative; z-index: 2;">

            <div class="flex items-center gap-3 min-w-0 flex-1">
                <button type="button" wire:click="closeQuickReview"
                    class="review-icon-btn"
                    style="background-color: transparent; color: var(--color-icon);"
                    onmouseover="this.style.backgroundColor='var(--sidebar-color-hover)'; this.style.color='var(--color-icon-hover)';"
                    onmouseout="this.style.backgroundColor='transparent'; this.style.color='var(--color-icon)';">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 18L18 6M6 6l12 12"/></svg>
                </button>

                @if($viewingStudent)
                    <div class="min-w-0">
                        <h2 class="text-sm font-semibold leading-tight truncate" style="color: var(--color-primary-2);">
                            {{ $viewingStudent->name }} {{ $viewingStudent->last_name_paterno }}
                        </h2>
                        <p class="text-xs mt-0.5 truncate" style="color: var(--color-secondary);">
                            {{ $viewingStudent->career->name ?? '—' }} · {{ $viewingStudent->control_number }}
                        </p>
                    </div>
                @endif
            </div>

            @if($viewingStudent)
                <button type="button" wire:click="exportStudentPDF({{ $viewingStudent->id }})"
                    class="inline-flex items-center gap-2 px-4 h-9 rounded-full text-sm font-medium transition-colors flex-shrink-0"
                    style="background-color: var(--color-bg); color: var(--color-primary-2); outline: none;"
                    onmouseover="this.style.backgroundColor='var(--color-card-bg-hover)'"
                    onmouseout="this.style.backgroundColor='var(--color-bg)'">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none">
                        <path d="M12 7L12 14M12 14L15 11M12 14L9 11" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M16 17H12H8" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                        <path d="M22 12C22 16.714 22 19.0711 20.5355 20.5355C19.0711 22 16.714 22 12 22C7.28595 22 4.92893 22 3.46447 20.5355C2 19.0711 2 16.714 2 12C2 7.28595 2 4.92893 3.46447 3.46447C4.92893 2 7.28595 2 12 2C16.714 2 19.0711 2 20.5355 3.46447C21.5093 4.43821 21.8356 5.80655 21.9449 8" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                    </svg>
                    <span class="hidden lg:inline">Descargar seguimiento</span>
                </button>
            @endif
        </div>

        @if(!$viewingStudent)
            <div class="flex-1 flex items-center justify-center">
                <p class="text-sm" style="color: var(--color-secondary);">No se encontró información del estudiante.</p>
            </div>
        @else
            @php
                $viewingIndividualFile = $this->getViewingIndividualFile();
            @endphp

            {{-- ── Barra 2: buscador + documento actual (ancho completo) ── --}}
            <div class="flex items-center gap-3 px-4 sm:px-6 py-3 flex-shrink-0"
                style="background-color: var(--sidebar-color-bg); box-shadow: 0 1px 3px rgba(0,0,0,0.05); position: relative; z-index: 1; min-height: 58px;">

                {{-- En mobile el buscador vive dentro de la hoja "Documentos"
                     (ver sheet más abajo) — aquí no cabe junto a nav+título. --}}
                <div class="hidden sm:flex topbar-search-input-wrap" style="max-width: 220px; flex-shrink: 0;">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16.6725 16.6412L21 21"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11C19 15.4183 15.4183 19 11 19C6.58172 19 3 15.4183 3 11C3 6.58172 6.58172 3 11 3C15.4183 3 19 6.58172 19 11Z"/>
                    </svg>
                    <input type="text" wire:model.live.debounce.300ms="reviewDocSearch"
                        placeholder="Buscar documento..."
                        class="topbar-search-input review-field">
                </div>

                @if($quickReviewDoc)
                    <div class="flex items-center gap-1.5 flex-shrink-0">
                        <button type="button" wire:click="navigateToPreviousDoc" @disabled(!$previousPendingDoc) class="review-icon-btn" title="Documento anterior">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 18l-6-6 6-6"/></svg>
                        </button>
                        <button type="button" wire:click="navigateToNextDoc" @disabled(!$nextPendingDoc) class="review-icon-btn" title="Siguiente documento">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 18l6-6-6-6"/></svg>
                        </button>
                    </div>

                    <h3 class="text-sm font-semibold truncate flex-1 min-w-0" style="color: var(--color-primary-2);">
                        {{ $quickReviewDoc->file->name ?? $quickReviewDoc->name }}
                    </h3>

                    <span class="status-badge {{ $quickReviewDoc->status === 'revisado' ? 'status-badge--approved' : ($quickReviewDoc->status === 'rechazado' ? 'status-badge--rejected' : 'status-badge--pending') }} flex-shrink-0">
                        <span class="status-badge-dot"></span>
                        @if($quickReviewDoc->status === 'revisado') Aprobado
                        @elseif($quickReviewDoc->status === 'rechazado') Rechazado
                        @else En revisión
                        @endif
                    </span>
                @elseif($viewingIndividualFile)
                    <h3 class="text-sm font-semibold truncate flex-1 min-w-0" style="color: var(--color-primary-2);">
                        {{ $viewingIndividualFile->name }}
                    </h3>

                    <span class="status-badge {{ $currentIndividualUpload ? 'status-badge--approved' : '' }} flex-shrink-0">
                        <span class="status-badge-dot"></span>
                        {{ $currentIndividualUpload ? 'Archivo subido' : 'Sin subir' }}
                    </span>
                @endif
            </div>

            <div class="flex-1 flex overflow-hidden">

                {{-- ── Columna izquierda: buscador (arriba, en barra 2) + documentos + estadísticas.
                     En mobile no hay espacio para ella — la hoja "Documentos" (dock de abajo) hace su función. ── --}}
                <div class="hidden sm:flex flex-col overflow-hidden flex-shrink-0"
                    style="width: var(--sidebar-width); background-color: var(--sidebar-color-bg); box-shadow: 1px 0 3px rgba(0,0,0,0.04); position: relative; z-index: 1;">

                    @php
                        $studentDocs      = $this->getFilteredStudentDocuments($viewingStudent->id);
                        $individualFiles  = $this->getFilteredIndividualFiles($viewingStudent->id);
                    @endphp

                    <div class="flex-1 overflow-y-auto px-3 pt-3 pb-2 space-y-1">
                        @if($individualFiles->count())
                            <p class="px-3 pb-1 pt-1 text-xs font-semibold uppercase tracking-wide" style="color: var(--color-secondary);">Individuales</p>
                            @foreach($individualFiles as $file)
                                <button type="button"
                                    wire:click="viewIndividualFile({{ $file->id }})"
                                    class="review-nav-item {{ $viewingIndividualFileId === $file->id ? 'is-active' : '' }}">
                                    <span class="status-badge-dot flex-shrink-0" style="color: {{ $file->uploaded ? '#16A34A' : '#D1D5DB' }};"></span>
                                    <span class="truncate flex-1">{{ $file->name }}</span>
                                </button>
                            @endforeach
                            <div style="height: 8px;"></div>
                        @endif

                        @forelse($studentDocs as $doc)
                            @php
                                $docColor = $doc->status === 'revisado' ? '#16A34A' : ($doc->status === 'rechazado' ? '#DC2626' : '#D97706');
                            @endphp
                            <button type="button"
                                wire:click="reviewDocFromStudent({{ $viewingStudent->id }}, {{ $doc->id }})"
                                class="review-nav-item {{ $quickReviewDoc && $quickReviewDoc->id === $doc->id ? 'is-active' : '' }}">
                                <span class="status-badge-dot flex-shrink-0" style="color: {{ $docColor }};"></span>
                                <span class="truncate flex-1">{{ $doc->file->name ?? $doc->name }}</span>
                            </button>
                        @empty
                            @if(!$individualFiles->count())
                                <div class="text-center px-3 py-10">
                                    <p class="text-xs" style="color: var(--color-secondary);">
                                        @if($reviewDocSearch !== '')
                                            No se encontraron documentos con "{{ $reviewDocSearch }}".
                                        @else
                                            Este estudiante todavía no ha subido documentos.
                                        @endif
                                    </p>
                                </div>
                            @endif
                        @endforelse
                    </div>

                    <div class="pt-2 pb-3 flex-shrink-0" style="box-shadow: 0 -1px 3px rgba(0,0,0,0.04);">
                        <div class="flex items-center gap-2.5 px-4 pt-2 py-1.5">
                            <span class="status-badge-dot" style="color: #16A34A; width: 7px; height: 7px;"></span>
                            <span class="text-sm font-semibold" style="color: var(--color-primary-2);">{{ $viewingStudent->approved_count ?? 0 }}</span>
                            <span class="text-sm" style="color: var(--color-secondary);">Aprobados</span>
                        </div>
                        <div class="flex items-center gap-2.5 px-4 py-1.5">
                            <span class="status-badge-dot" style="color: #D97706; width: 7px; height: 7px;"></span>
                            <span class="text-sm font-semibold" style="color: var(--color-primary-2);">{{ $viewingStudent->pending_count ?? 0 }}</span>
                            <span class="text-sm" style="color: var(--color-secondary);">Pendientes</span>
                        </div>
                        <div class="flex items-center gap-2.5 px-4 py-1.5">
                            <span class="status-badge-dot" style="color: #DC2626; width: 7px; height: 7px;"></span>
                            <span class="text-sm font-semibold" style="color: var(--color-primary-2);">{{ $viewingStudent->rejected_count ?? 0 }}</span>
                            <span class="text-sm" style="color: var(--color-secondary);">Rechazados</span>
                        </div>
                    </div>
                </div>

                {{-- ── Columna central: documento ── --}}
                <div class="flex-1 flex flex-col overflow-hidden min-w-0 relative">
                    @if($quickReviewDoc || $viewingIndividualFile)
                        <div class="flex-1 min-h-0" style="background-color: var(--color-contenedor-view);">
                            @if($quickReviewPreviewUrl && pathinfo($quickReviewPreviewUrl, PATHINFO_EXTENSION) === 'pdf')
                                <div wire:key="pdf-viewer-{{ $quickReviewDoc?->id }}-{{ $viewingIndividualFileId }}-{{ $quickReviewPreviewUrl }}"
                                    wire:ignore
                                    x-data
                                    x-init="renderReviewPdf(@js($quickReviewPreviewUrl), $el); initPdfPinchZoom($el)"
                                    class="pdf-render-target w-full h-full overflow-y-auto overflow-x-auto"
                                    style="background-color: var(--color-contenedor-view); padding: 24px;">
                                </div>

                                {{-- Solo escritorio: en mobile el pellizco (dos dedos) hace
                                     zoom y estos botones chocarían con el dock flotante. --}}
                                <div class="hidden sm:flex absolute bottom-5 right-5 items-center gap-1 rounded-full px-1.5 py-1.5"
                                    style="background-color: var(--color-card-bg); box-shadow: 0 2px 10px rgba(0,0,0,0.15);">
                                    <button type="button" class="review-icon-btn" style="width: 28px; height: 28px; background-color: transparent;"
                                        onclick="zoomReviewPdf(document.querySelector('.pdf-render-target'), -0.15)"
                                        onmouseover="this.style.backgroundColor='var(--color-bg)'"
                                        onmouseout="this.style.backgroundColor='transparent'">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/></svg>
                                    </button>
                                    <span id="pdf-zoom-label" class="text-xs" style="color: var(--color-secondary); min-width: 38px; text-align: center;">100%</span>
                                    <button type="button" class="review-icon-btn" style="width: 28px; height: 28px; background-color: transparent;"
                                        onclick="zoomReviewPdf(document.querySelector('.pdf-render-target'), 0.15)"
                                        onmouseover="this.style.backgroundColor='var(--color-bg)'"
                                        onmouseout="this.style.backgroundColor='transparent'">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14M5 12h14"/></svg>
                                    </button>
                                </div>
                            @elseif($quickReviewPreviewUrl)
                                <div class="flex flex-col items-center justify-center h-full text-center px-6">
                                    <div class="w-14 h-14 rounded-2xl flex items-center justify-center mb-3"
                                        style="background-color: var(--color-card-bg);">
                                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color: var(--color-secondary);">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                        </svg>
                                    </div>
                                    <p class="text-sm font-medium mb-1" style="color: var(--color-primary-2);">Vista previa no disponible</p>
                                    <p class="text-xs mb-3" style="color: var(--color-secondary);">Este formato no puede visualizarse en el navegador</p>
                                    <a href="{{ $quickReviewPreviewUrl }}" download
                                        class="btn-primary" style="height: 36px; padding: 0 16px; font-size: 13px;">
                                        Descargar para ver
                                    </a>
                                </div>
                            @else
                                <div class="flex flex-col items-center justify-center h-full text-center px-6">
                                    <div class="w-14 h-14 rounded-2xl flex items-center justify-center mb-3"
                                        style="background-color: var(--color-card-bg);">
                                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color: var(--color-secondary);">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                                        </svg>
                                    </div>
                                    <p class="text-sm font-medium" style="color: var(--color-primary-2);">Todavía no hay ningún archivo subido</p>
                                    <p class="text-xs mt-1" style="color: var(--color-secondary);">Sube uno desde el panel de la derecha.</p>
                                </div>
                            @endif
                        </div>
                    @else
                        <div class="flex-1 flex flex-col items-center justify-center text-center px-6"
                            style="background-color: var(--color-contenedor-view);">
                            <div class="w-14 h-14 rounded-2xl flex items-center justify-center mb-3"
                                style="background-color: var(--color-icon-bg);">
                                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color: var(--color-secondary);">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                            </div>
                            <p class="text-sm font-medium" style="color: var(--color-secondary);">
                                Este estudiante todavía no ha subido documentos para revisar.
                            </p>
                        </div>
                    @endif
                </div>

                {{-- ── Columna derecha: acciones. En mobile vive en la hoja
                     "Revisar" (dock de abajo) en vez de esta columna. ── --}}
                <div class="hidden sm:flex flex-col overflow-y-auto flex-shrink-0"
                    style="width: var(--sidebar-width); background-color: var(--sidebar-color-bg); box-shadow: -1px 0 3px rgba(0,0,0,0.04);">

                    @if($viewingIndividualFile)
                        <div class="flex flex-col flex-1">
                            <div class="p-4">
                                <p class="text-xs font-semibold mb-1 flex items-center gap-1.5"
                                    style="color: var(--color-primary-2);">
                                    <svg class="w-3.5 h-3.5 flex-shrink-0" style="color: var(--color-secondary);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                                    </svg>
                                    Subir Documento
                                </p>
                                <p class="text-xs mb-3" style="color: var(--color-secondary);">Solo el administrador puede subir este archivo.</p>

                                @if($currentIndividualUpload)
                                    <div class="mb-3 p-2.5 rounded-lg flex items-center justify-between gap-2" style="background-color: var(--color-bg);">
                                        <div class="min-w-0">
                                            <p class="text-xs font-medium truncate" style="color: var(--color-primary-2);">{{ $currentIndividualUpload->name_file }}</p>
                                            <p class="text-xs" style="color: var(--color-secondary);">{{ $currentIndividualUpload->created_at->diffForHumans() }}</p>
                                        </div>
                                        <button wire:click="deleteIndividualFile" wire:confirm="¿Eliminar este archivo?"
                                            class="review-icon-btn flex-shrink-0" style="width: 28px; height: 28px; background-color: transparent; color: #DC2626;"
                                            onmouseover="this.style.backgroundColor='rgba(220,38,38,0.1)'"
                                            onmouseout="this.style.backgroundColor='transparent'">
                                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </div>
                                @endif

                                <input type="file" wire:model="individualUploadFile" accept=".pdf,.doc,.docx"
                                    class="w-full text-xs file:mr-3 file:py-2 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:cursor-pointer cursor-pointer"
                                    style="color: var(--color-secondary);">

                                @error('individualUploadFile')
                                    <p class="mt-1.5 text-xs" style="color: #DC2626;">{{ $message }}</p>
                                @enderror

                                @if($individualUploadFile)
                                    <button wire:click="uploadIndividualFile" wire:loading.attr="disabled" class="btn-primary w-full mt-3" style="height: 38px; font-size: 13px; outline: none;">
                                        <span wire:loading.remove wire:target="uploadIndividualFile">Subir Archivo</span>
                                        <span wire:loading wire:target="uploadIndividualFile">Subiendo...</span>
                                    </button>
                                @endif
                            </div>
                        </div>
                    @elseif($quickReviewDoc)
                        <div class="flex flex-col flex-1">

                            {{-- Observaciones --}}
                            <div class="p-4" style="box-shadow: 0 1px 3px rgba(0,0,0,0.05);"
                                wire:key="obs-{{ $quickReviewDoc->id }}-{{ $quickReviewDoc->comments }}"
                                x-data="{ original: @js($quickReviewDoc->comments ?? ''), changed: false }">
                                <p class="text-xs font-semibold mb-1 flex items-center gap-1.5"
                                    style="color: var(--color-primary-2);">
                                    <svg class="w-3.5 h-3.5 flex-shrink-0" style="color: var(--color-secondary);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/>
                                    </svg>
                                    Observaciones
                                </p>
                                <p class="text-xs mb-2" style="color: var(--color-secondary);">Visible para el estudiante.</p>

                                <textarea wire:model.defer="quickReviewComments"
                                    placeholder="Escribe aquí..."
                                    rows="4"
                                    @input="changed = ($event.target.value !== original)"
                                    class="review-field w-full px-3 py-2 rounded-lg text-xs resize-none"
                                    style="color: var(--color-primary-2);">
                                </textarea>

                                @error('quickReviewComments')
                                    <p class="mt-1.5 text-xs flex items-center gap-1.5" style="color: #ee6e6c;">
                                        <svg class="w-3.5 h-3.5 flex-shrink-0" viewBox="0 0 20 20" fill="#ee6e6c">
                                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.72-1.36 3.485 0l6.518 11.59c.75 1.335-.213 2.971-1.742 2.971H3.48c-1.529 0-2.492-1.636-1.742-2.971L8.257 3.1zM11 13a1 1 0 10-2 0 1 1 0 002 0zm-1-8a1 1 0 00-.993.883L9 6v4a1 1 0 001.993.117L11 10V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                        </svg>
                                        {{ $message }}
                                    </p>
                                @enderror

                                <button wire:click="saveComments"
                                    x-show="changed" x-cloak
                                    class="mt-2 px-4 h-8 text-xs font-semibold rounded-full transition-all duration-200 self-end"
                                    style="color: var(--color-primary-2); background-color: var(--color-bg); outline: none;"
                                    onmouseover="this.style.backgroundColor='var(--color-card-bg-hover)'"
                                    onmouseout="this.style.backgroundColor='var(--color-bg)'">
                                    Guardar
                                </button>
                            </div>

                            {{-- Fecha límite personalizada --}}
                            @php
                                $periodStart = optional($quickReviewDoc->file->period)->start_date;
                                $periodEnd   = optional($quickReviewDoc->file->period)->end_date;
                                $currentDateValue = $editingDates[$quickReviewDoc->id] ?? '';
                            @endphp
                            <div class="p-4"
                                style="box-shadow: 0 1px 3px rgba(0,0,0,0.05);"
                                wire:key="fecha-{{ $quickReviewDoc->id }}-{{ $quickReviewDoc->custom_limit_date }}"
                                x-data="{ original: @js($currentDateValue), changed: false }"
                                @date-change="changed = ($event.detail !== original)">
                                <p class="text-xs font-semibold mb-2 flex items-center gap-1.5"
                                    style="color: var(--color-primary-2);">
                                    <svg class="w-3.5 h-3.5 flex-shrink-0" style="color: var(--color-secondary);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    Fecha Límite Personalizada
                                </p>

                                <x-date-picker
                                    wire-model="editingDates.{{ $quickReviewDoc->id }}"
                                    :value="$currentDateValue"
                                    :min="$periodStart ? \Carbon\Carbon::parse($periodStart)->format('Y-m-d') : null"
                                    :max="$periodEnd ? \Carbon\Carbon::parse($periodEnd)->format('Y-m-d') : null"
                                    :overlay="true"
                                    class="mb-2" />

                                <button wire:click="updateDocumentDate({{ $quickReviewDoc->id }})"
                                    x-show="changed" x-cloak
                                    class="px-4 h-8 text-xs font-semibold rounded-full transition-all duration-200 self-end"
                                    style="color: var(--color-primary-2); background-color: var(--color-bg); outline: none;"
                                    onmouseover="this.style.backgroundColor='var(--color-card-bg-hover)'"
                                    onmouseout="this.style.backgroundColor='var(--color-bg)'">
                                    Actualizar
                                </button>
                            </div>

                            {{-- Botones aprobar / rechazar --}}
                            <div class="p-4 mt-auto">
                                <div class="grid grid-cols-2 gap-2">
                                    <button wire:click="quickApproveDocument" class="btn-success" style="height: 40px; font-size: 13px; outline: none;">
                                        Aprobar
                                    </button>
                                    <button wire:click="quickRejectDocument" class="btn-danger" style="height: 40px; font-size: 13px; outline: none;">
                                        Rechazar
                                    </button>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="flex-1 flex items-center justify-center p-6 text-center">
                            <p class="text-xs" style="color: var(--color-secondary);">
                                Selecciona un documento de la lista para revisarlo.
                            </p>
                        </div>
                    @endif
                </div>

            </div>

            {{-- ── Mobile: dock flotante (mismo "liquid glass" del visor de
                 Documentos / mobile-bottom-nav) + hojas inferiores para lo que
                 en escritorio vive en las columnas izquierda/derecha. La lista
                 de documentos puede ser larga, así que en vez de píldoras (no
                 caben todas) se abre como hoja con su propio scroll. ── --}}
            @php
                $rvGlass = 'border border-white/30 bg-linear-to-b from-[rgba(255,255,255,0.45)] to-[rgba(255,255,255,0.22)] shadow-[inset_0_1px_0_0_rgba(255,255,255,0.4),0_20px_45px_-15px_rgba(0,0,0,0.18)] backdrop-blur-[20px] backdrop-saturate-[180%] dark:border-white/8 dark:from-[rgba(23,23,23,0.45)] dark:to-[rgba(23,23,23,0.22)] dark:shadow-[inset_0_1px_0_0_rgba(255,255,255,0.06),0_20px_45px_-15px_rgba(0,0,0,0.5)]';
            @endphp

            <div class="fixed bottom-[calc(1rem+env(safe-area-inset-bottom))] left-1/2 -translate-x-1/2 z-20 flex items-center gap-2 sm:hidden">
                <nav class="viewer-dock {{ $rvGlass }}" aria-label="Documentos y revisión">
                    <button type="button" @click="docsSheetOpen = true" class="viewer-dock-item">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <span class="text-[10px] font-medium leading-none whitespace-nowrap">Documentos</span>
                    </button>

                    @if($quickReviewDoc || $viewingIndividualFile)
                        <button type="button" @click="detailSheetOpen = true" class="viewer-dock-item">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                <path d="M9 9h1M9 12.5h6M9 16h6" stroke-linecap="round"/>
                            </svg>
                            <span class="text-[10px] font-medium leading-none whitespace-nowrap">Revisar</span>
                        </button>
                    @endif
                </nav>
            </div>

            {{-- ── Hoja "Documentos": fondo + panel, mismo patrón de sheet
                 (drag handle, header, lista con su propio scroll). ── --}}
            <div x-show="docsSheetOpen" x-cloak x-transition.opacity
                class="fixed inset-0 z-30 sm:hidden" style="background-color: rgba(0,0,0,0.5); backdrop-filter: blur(4px); -webkit-backdrop-filter: blur(4px);"
                @click="docsSheetOpen = false"></div>

            <div x-show="docsSheetOpen" x-cloak
                x-transition:enter="transition ease-out duration-200" x-transition:enter-start="translate-y-full" x-transition:enter-end="translate-y-0"
                x-transition:leave="transition ease-in duration-150" x-transition:leave-start="translate-y-0" x-transition:leave-end="translate-y-full"
                class="fixed inset-x-0 bottom-0 z-40 flex flex-col sm:hidden rounded-t-3xl overflow-hidden"
                style="max-height: 80dvh; background-color: var(--color-modal-bg); box-shadow: 0 -10px 40px rgba(0,0,0,0.3);">

                <div class="w-10 h-1.5 rounded-full mx-auto mt-3 mb-1 flex-shrink-0" style="background-color: var(--color-border-hover);"></div>

                <div class="flex items-center justify-between gap-3 px-4 py-3 flex-shrink-0">
                    <h3 class="text-sm font-semibold" style="color: var(--color-primary-2);">Documentos</h3>
                    <button type="button" @click="docsSheetOpen = false" class="review-icon-btn" style="background-color: transparent; color: var(--color-icon);">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <div class="px-4 pb-3 flex-shrink-0">
                    <div class="topbar-search-input-wrap">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16.6725 16.6412L21 21"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11C19 15.4183 15.4183 19 11 19C6.58172 19 3 15.4183 3 11C3 6.58172 6.58172 3 11 3C15.4183 3 19 6.58172 19 11Z"/>
                        </svg>
                        <input type="text" wire:model.live.debounce.300ms="reviewDocSearch"
                            placeholder="Buscar documento..."
                            class="topbar-search-input review-field">
                    </div>
                </div>

                <div class="flex-1 overflow-y-auto px-3 pb-2 space-y-1">
                    @if($individualFiles->count())
                        <p class="px-3 pb-1 pt-1 text-xs font-semibold uppercase tracking-wide" style="color: var(--color-secondary);">Individuales</p>
                        @foreach($individualFiles as $file)
                            <button type="button"
                                wire:click="viewIndividualFile({{ $file->id }})"
                                @click="docsSheetOpen = false"
                                class="review-nav-item {{ $viewingIndividualFileId === $file->id ? 'is-active' : '' }}">
                                <span class="status-badge-dot flex-shrink-0" style="color: {{ $file->uploaded ? '#16A34A' : '#D1D5DB' }};"></span>
                                <span class="truncate flex-1">{{ $file->name }}</span>
                            </button>
                        @endforeach
                        <div style="height: 8px;"></div>
                    @endif

                    @forelse($studentDocs as $doc)
                        @php
                            $docColorMobile = $doc->status === 'revisado' ? '#16A34A' : ($doc->status === 'rechazado' ? '#DC2626' : '#D97706');
                        @endphp
                        <button type="button"
                            wire:click="reviewDocFromStudent({{ $viewingStudent->id }}, {{ $doc->id }})"
                            @click="docsSheetOpen = false"
                            class="review-nav-item {{ $quickReviewDoc && $quickReviewDoc->id === $doc->id ? 'is-active' : '' }}">
                            <span class="status-badge-dot flex-shrink-0" style="color: {{ $docColorMobile }};"></span>
                            <span class="truncate flex-1">{{ $doc->file->name ?? $doc->name }}</span>
                        </button>
                    @empty
                        @if(!$individualFiles->count())
                            <div class="text-center px-3 py-10">
                                <p class="text-xs" style="color: var(--color-secondary);">
                                    @if($reviewDocSearch !== '')
                                        No se encontraron documentos con "{{ $reviewDocSearch }}".
                                    @else
                                        Este estudiante todavía no ha subido documentos.
                                    @endif
                                </p>
                            </div>
                        @endif
                    @endforelse
                </div>

                <div class="pt-2 flex-shrink-0" style="padding-bottom: calc(0.75rem + env(safe-area-inset-bottom)); box-shadow: 0 -1px 3px rgba(0,0,0,0.04);">
                    <div class="flex items-center gap-2.5 px-4 pt-2 py-1.5">
                        <span class="status-badge-dot" style="color: #16A34A; width: 7px; height: 7px;"></span>
                        <span class="text-sm font-semibold" style="color: var(--color-primary-2);">{{ $viewingStudent->approved_count ?? 0 }}</span>
                        <span class="text-sm" style="color: var(--color-secondary);">Aprobados</span>
                    </div>
                    <div class="flex items-center gap-2.5 px-4 py-1.5">
                        <span class="status-badge-dot" style="color: #D97706; width: 7px; height: 7px;"></span>
                        <span class="text-sm font-semibold" style="color: var(--color-primary-2);">{{ $viewingStudent->pending_count ?? 0 }}</span>
                        <span class="text-sm" style="color: var(--color-secondary);">Pendientes</span>
                    </div>
                    <div class="flex items-center gap-2.5 px-4 py-1.5">
                        <span class="status-badge-dot" style="color: #DC2626; width: 7px; height: 7px;"></span>
                        <span class="text-sm font-semibold" style="color: var(--color-primary-2);">{{ $viewingStudent->rejected_count ?? 0 }}</span>
                        <span class="text-sm" style="color: var(--color-secondary);">Rechazados</span>
                    </div>
                </div>
            </div>

            {{-- ── Hoja "Revisar": mismo contenido que la columna derecha de
                 escritorio (observaciones, fecha límite, aprobar/rechazar, o
                 el formulario de carga individual), en formato hoja. ── --}}
            @if($quickReviewDoc || $viewingIndividualFile)
                <div x-show="detailSheetOpen" x-cloak x-transition.opacity
                    class="fixed inset-0 z-30 sm:hidden" style="background-color: rgba(0,0,0,0.5); backdrop-filter: blur(4px); -webkit-backdrop-filter: blur(4px);"
                    @click="detailSheetOpen = false"></div>

                <div x-show="detailSheetOpen" x-cloak
                    x-transition:enter="transition ease-out duration-200" x-transition:enter-start="translate-y-full" x-transition:enter-end="translate-y-0"
                    x-transition:leave="transition ease-in duration-150" x-transition:leave-start="translate-y-0" x-transition:leave-end="translate-y-full"
                    class="fixed inset-x-0 bottom-0 z-40 flex flex-col sm:hidden rounded-t-3xl overflow-hidden"
                    style="max-height: 85dvh; background-color: var(--color-modal-bg); box-shadow: 0 -10px 40px rgba(0,0,0,0.3);">

                    <div class="w-10 h-1.5 rounded-full mx-auto mt-3 mb-1 flex-shrink-0" style="background-color: var(--color-border-hover);"></div>

                    <div class="flex items-center justify-between gap-3 px-4 py-3 flex-shrink-0">
                        <h3 class="text-sm font-semibold truncate" style="color: var(--color-primary-2);">
                            {{ $viewingIndividualFile ? $viewingIndividualFile->name : ($quickReviewDoc->file->name ?? $quickReviewDoc->name) }}
                        </h3>
                        <button type="button" @click="detailSheetOpen = false" class="review-icon-btn flex-shrink-0" style="background-color: transparent; color: var(--color-icon);">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>

                    <div class="flex-1 overflow-y-auto">
                        @if($viewingIndividualFile)
                            <div class="p-4">
                                <p class="text-xs font-semibold mb-1 flex items-center gap-1.5" style="color: var(--color-primary-2);">
                                    <svg class="w-3.5 h-3.5 flex-shrink-0" style="color: var(--color-secondary);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                                    </svg>
                                    Subir Documento
                                </p>
                                <p class="text-xs mb-3" style="color: var(--color-secondary);">Solo el administrador puede subir este archivo.</p>

                                @if($currentIndividualUpload)
                                    <div class="mb-3 p-2.5 rounded-lg flex items-center justify-between gap-2" style="background-color: var(--color-bg);">
                                        <div class="min-w-0">
                                            <p class="text-xs font-medium truncate" style="color: var(--color-primary-2);">{{ $currentIndividualUpload->name_file }}</p>
                                            <p class="text-xs" style="color: var(--color-secondary);">{{ $currentIndividualUpload->created_at->diffForHumans() }}</p>
                                        </div>
                                        <button wire:click="deleteIndividualFile" wire:confirm="¿Eliminar este archivo?"
                                            class="review-icon-btn flex-shrink-0" style="width: 28px; height: 28px; background-color: transparent; color: #DC2626;">
                                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </div>
                                @endif

                                <input type="file" wire:model="individualUploadFile" accept=".pdf,.doc,.docx"
                                    class="w-full text-xs file:mr-3 file:py-2 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:cursor-pointer cursor-pointer"
                                    style="color: var(--color-secondary);">

                                @error('individualUploadFile')
                                    <p class="mt-1.5 text-xs" style="color: #DC2626;">{{ $message }}</p>
                                @enderror

                                @if($individualUploadFile)
                                    <button wire:click="uploadIndividualFile" wire:loading.attr="disabled" class="btn-primary w-full mt-3" style="height: 38px; font-size: 13px; outline: none;">
                                        <span wire:loading.remove wire:target="uploadIndividualFile">Subir Archivo</span>
                                        <span wire:loading wire:target="uploadIndividualFile">Subiendo...</span>
                                    </button>
                                @endif
                            </div>
                        @else
                            <div class="p-4" x-data="{ original: @js($quickReviewDoc->comments ?? ''), changed: false }">
                                <p class="text-xs font-semibold mb-1 flex items-center gap-1.5" style="color: var(--color-primary-2);">
                                    <svg class="w-3.5 h-3.5 flex-shrink-0" style="color: var(--color-secondary);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/>
                                    </svg>
                                    Observaciones
                                </p>
                                <p class="text-xs mb-2" style="color: var(--color-secondary);">Visible para el estudiante.</p>

                                <textarea wire:model.defer="quickReviewComments"
                                    placeholder="Escribe aquí..."
                                    rows="4"
                                    @input="changed = ($event.target.value !== original)"
                                    class="review-field w-full px-3 py-2 rounded-lg text-xs resize-none"
                                    style="color: var(--color-primary-2);">
                                </textarea>

                                @error('quickReviewComments')
                                    <p class="mt-1.5 text-xs flex items-center gap-1.5" style="color: #ee6e6c;">
                                        <svg class="w-3.5 h-3.5 flex-shrink-0" viewBox="0 0 20 20" fill="#ee6e6c">
                                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.72-1.36 3.485 0l6.518 11.59c.75 1.335-.213 2.971-1.742 2.971H3.48c-1.529 0-2.492-1.636-1.742-2.971L8.257 3.1zM11 13a1 1 0 10-2 0 1 1 0 002 0zm-1-8a1 1 0 00-.993.883L9 6v4a1 1 0 001.993.117L11 10V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                        </svg>
                                        {{ $message }}
                                    </p>
                                @enderror

                                <button wire:click="saveComments"
                                    x-show="changed" x-cloak
                                    class="mt-2 px-4 h-8 text-xs font-semibold rounded-full transition-all duration-200"
                                    style="color: var(--color-primary-2); background-color: var(--color-bg); outline: none;">
                                    Guardar
                                </button>
                            </div>

                            @php
                                $periodStartMobile = optional($quickReviewDoc->file->period)->start_date;
                                $periodEndMobile   = optional($quickReviewDoc->file->period)->end_date;
                                $currentDateValueMobile = $editingDates[$quickReviewDoc->id] ?? '';
                            @endphp
                            @if($quickReviewDoc->file->upload_mode !== 'admin_only')
                                <div class="p-4" x-data="{ original: @js($currentDateValueMobile), changed: false }" @date-change="changed = ($event.detail !== original)">
                                    <p class="text-xs font-semibold mb-2 flex items-center gap-1.5" style="color: var(--color-primary-2);">
                                        <svg class="w-3.5 h-3.5 flex-shrink-0" style="color: var(--color-secondary);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                        Fecha Límite Personalizada
                                    </p>

                                    <x-date-picker
                                        wire-model="editingDates.{{ $quickReviewDoc->id }}"
                                        :value="$currentDateValueMobile"
                                        :min="$periodStartMobile ? \Carbon\Carbon::parse($periodStartMobile)->format('Y-m-d') : null"
                                        :max="$periodEndMobile ? \Carbon\Carbon::parse($periodEndMobile)->format('Y-m-d') : null"
                                        :overlay="true"
                                        class="mb-2" />

                                    <button wire:click="updateDocumentDate({{ $quickReviewDoc->id }})"
                                        x-show="changed" x-cloak
                                        class="px-4 h-8 text-xs font-semibold rounded-full transition-all duration-200"
                                        style="color: var(--color-primary-2); background-color: var(--color-bg); outline: none;">
                                        Actualizar
                                    </button>
                                </div>
                            @endif

                            <div class="p-4" style="padding-bottom: calc(1rem + env(safe-area-inset-bottom));">
                                <div class="grid grid-cols-2 gap-2">
                                    <button wire:click="quickApproveDocument" @click="detailSheetOpen = false" class="btn-success" style="height: 40px; font-size: 13px; outline: none;">
                                        Aprobar
                                    </button>
                                    <button wire:click="quickRejectDocument" class="btn-danger" style="height: 40px; font-size: 13px; outline: none;">
                                        Rechazar
                                    </button>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            @endif
        @endif
    </div>
</flux:modal>
