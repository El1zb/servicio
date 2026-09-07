{{-- =================== VISOR DE DOCUMENTOS (pantalla completa) ===================
     Mismo "look" que el visor de revisión del admin (quick-review-modal.blade.php):
     sin panel derecho (ahí van acciones exclusivas de admin) y, a diferencia de
     ese, sin navegación entre documentos — este visor está limitado a los
     archivos de LA CARD desde la que se abrió (hasta 3: Word / PDF / Mi archivo). --}}
@if($isViewerOpen)
    @php $viewerDoc = $this->getViewerDocument(); @endphp

    @if($viewerDoc)
        @php
            $viewerFiles = $this->getViewerFiles($viewerDoc);
            $currentFile = collect($viewerFiles)->firstWhere('path', $viewerFilePath);
            $vExt        = $currentFile ? strtoupper(pathinfo($currentFile['path'], PATHINFO_EXTENSION)) : null;
        @endphp

        <flux:modal
            wire:model="isViewerOpen"
            :dismissible="false"
            :closable="false"
            class="!p-0 !max-w-none !rounded-none !shadow-none w-screen h-screen !m-0 overflow-hidden !outline-none"
            style="outline: none; max-width: 100vw; max-height: 100vh; margin: 0; inset: 0;">

            <div class="flex flex-col"
                style="height: 100vh;"
                x-data
                @keydown.window="
                    const el = document.activeElement;
                    const typing = el && (el.tagName === 'INPUT' || el.tagName === 'TEXTAREA' || el.isContentEditable);
                    if ($wire.isViewerOpen && !typing) {
                        if ($event.key === 'ArrowLeft') {
                            const btn = document.querySelector('button[title=\'Archivo anterior\']');
                            if (btn && !btn.disabled) btn.click();
                        } else if ($event.key === 'ArrowRight') {
                            const btn = document.querySelector('button[title=\'Siguiente archivo\']');
                            if (btn && !btn.disabled) btn.click();
                        }
                    }
                ">

                @php
                    $vHasFile   = (bool) $viewerDoc->student_file_name;
                    $vIsAdmin   = $viewerDoc->file?->upload_mode === 'admin_only';
                    $vStatusMap = [
                        'revisado'    => ['label' => 'Aprobado',    'class' => 'status-badge--approved'],
                        'rechazado'   => ['label' => 'Rechazado',   'class' => 'status-badge--rejected'],
                        'en_revision' => ['label' => 'En revisión', 'class' => 'status-badge--review'],
                    ];
                @endphp

                {{-- ── Barra 2: navegación entre archivos + nombre · extensión ── --}}
                <div class="flex items-center gap-3 px-4 sm:px-6 py-3 flex-shrink-0"
                    style="background-color: var(--sidebar-color-bg); box-shadow: 0 1px 3px rgba(0,0,0,0.05); position: relative; z-index: 1; min-height: 58px;">

                    <div class="flex items-center gap-1.5 flex-shrink-0">
                        <button type="button" wire:click="closeViewer"
                            class="review-icon-btn"
                            style="background-color: transparent; color: var(--color-icon);"
                            onmouseover="this.style.backgroundColor='#EDEDED'; this.style.color='var(--color-icon-hover)';"
                            onmouseout="this.style.backgroundColor='transparent'; this.style.color='var(--color-icon)';">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                        <button type="button" wire:click="viewerNavigate('prev')" @disabled(!$this->viewerHasPrev()) class="review-icon-btn" title="Archivo anterior"
                            onmouseover="this.style.backgroundColor='#EDEDED'" onmouseout="this.style.backgroundColor=''">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 18l-6-6 6-6"/></svg>
                        </button>
                        <button type="button" wire:click="viewerNavigate('next')" @disabled(!$this->viewerHasNext()) class="review-icon-btn" title="Siguiente archivo"
                            onmouseover="this.style.backgroundColor='#EDEDED'" onmouseout="this.style.backgroundColor=''">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 18l6-6-6-6"/></svg>
                        </button>
                    </div>

                    <h3 class="text-sm font-semibold truncate flex-1 min-w-0" style="color: var(--color-primary-2);">
                        {{ $viewerDoc->name }}
                        @if($vExt)
                            <span style="color: var(--color-secondary); font-weight: 500;">· {{ $vExt }}</span>
                        @endif
                    </h3>

                    @if($vIsAdmin)
                        <span class="status-badge flex-shrink-0">
                            <span class="status-badge-dot"></span>
                            Informativo
                        </span>
                    @elseif($vHasFile)
                        <span class="status-badge {{ $vStatusMap[$viewerDoc->status]['class'] ?? '' }} flex-shrink-0">
                            <span class="status-badge-dot"></span>
                            {{ $vStatusMap[$viewerDoc->status]['label'] ?? '' }}
                        </span>
                    @else
                        <span class="status-badge status-badge--pending flex-shrink-0">
                            <span class="status-badge-dot"></span>
                            Sin entregar
                        </span>
                    @endif

                    @if($currentFile)
                        <a href="{{ route('files.show', ['path' => $currentFile['path']]) }}" download="{{ $currentFile['name'] }}"
                            class="review-icon-btn flex-shrink-0" title="Descargar"
                            onmouseover="this.style.backgroundColor='#EDEDED'" onmouseout="this.style.backgroundColor=''">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                            </svg>
                        </a>
                    @endif
                </div>

                {{-- ── Motivo del rechazo / observaciones — integrado aquí mismo, para
                     no tener que salir a un modal de comentarios aparte y volver. ── --}}
                @if(!empty(trim($viewerDoc->comments ?? '')))
                    <div class="px-4 sm:px-6 py-3 flex-shrink-0" style="background-color: rgba(220,38,38,0.06); border-bottom: 1px solid var(--color-border-hover);">
                        <p class="text-xs font-semibold mb-1" style="color: #DC2626;">
                            {{ $viewerDoc->status === 'rechazado' ? 'Motivo del rechazo' : 'Observaciones del revisor' }}
                        </p>
                        <p class="text-sm whitespace-pre-line" style="color: var(--color-primary-2);">{{ $viewerDoc->comments }}</p>
                    </div>
                @endif

                <div class="flex-1 flex overflow-hidden">

                    {{-- ── Columna izquierda: solo los archivos de esta card ── --}}
                    <div class="flex flex-col overflow-hidden flex-shrink-0"
                        style="width: var(--sidebar-width); background-color: var(--sidebar-color-bg); box-shadow: 1px 0 3px rgba(0,0,0,0.04); position: relative; z-index: 1;">

                        <div class="flex-1 overflow-y-auto px-3 pt-3 pb-2 space-y-1">
                            @foreach($viewerFiles as $file)
                                @php
                                    $fIsPdf  = str_ends_with(strtolower($file['path']), '.pdf');
                                    $fIsMine = $file['type'] === 'mine';
                                    $fLabel  = $fIsMine ? 'Mi archivo' : ($fIsPdf ? 'PDF' : 'Word');
                                    $fColor  = $fIsMine ? '#16A34A' : ($fIsPdf ? '#DC2626' : '#2563EB');
                                @endphp
                                <button type="button" wire:click="viewerSelectFile('{{ $file['path'] }}')"
                                    class="review-nav-item {{ $viewerFilePath === $file['path'] ? 'is-active' : '' }}">
                                    <span class="status-badge-dot flex-shrink-0" style="color: {{ $fColor }};"></span>
                                    <span class="truncate flex-1">{{ $fLabel }}</span>
                                </button>
                            @endforeach
                        </div>
                    </div>

                    {{-- ── Columna central: documento ── --}}
                    <div class="flex-1 flex flex-col overflow-hidden min-w-0 relative">

                        @if($currentFile)
                            @php $vFileExt = strtolower(pathinfo($currentFile['path'], PATHINFO_EXTENSION)); @endphp
                            <div class="flex-1 min-h-0" style="background-color: var(--color-contenedor-view);">
                                @if($vFileExt === 'pdf' || $vFileExt === 'docx')
                                    @php
                                        // El Word se sirve convertido a PDF (LibreOffice, ver
                                        // DocxToPdfConverter) y usa exactamente el mismo render
                                        // de canvas que un PDF real — misma fidelidad, mismo look.
                                        $vPdfUrl = route('files.show', array_filter([
                                            'path' => $currentFile['path'],
                                            'as'   => $vFileExt === 'docx' ? 'pdf' : null,
                                        ]));
                                    @endphp
                                    <div wire:key="viewer-pdf-{{ $currentFile['path'] }}"
                                        wire:ignore
                                        x-data
                                        x-init="renderReviewPdf(@js($vPdfUrl), $el)"
                                        class="pdf-render-target w-full h-full overflow-y-auto"
                                        style="background-color: var(--color-contenedor-view); padding: 24px;">
                                    </div>

                                    <div class="absolute bottom-5 right-5 flex items-center gap-1 rounded-full px-1.5 py-1.5"
                                        style="background-color: var(--color-card-bg); box-shadow: 0 2px 10px rgba(0,0,0,0.15);">
                                        <button type="button" class="review-icon-btn" style="width: 28px; height: 28px; background-color: transparent;"
                                            onclick="zoomReviewPdf(document.querySelector('.pdf-render-target'), -0.15)">
                                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/></svg>
                                        </button>
                                        <span id="pdf-zoom-label" class="text-xs" style="color: var(--color-secondary); min-width: 38px; text-align: center;">100%</span>
                                        <button type="button" class="review-icon-btn" style="width: 28px; height: 28px; background-color: transparent;"
                                            onclick="zoomReviewPdf(document.querySelector('.pdf-render-target'), 0.15)">
                                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14M5 12h14"/></svg>
                                        </button>
                                    </div>

                                @else
                                    <div class="flex flex-col items-center justify-center h-full text-center px-6">
                                        <div class="w-14 h-14 rounded-2xl flex items-center justify-center mb-3" style="background-color: var(--color-card-bg);">
                                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color: var(--color-secondary);">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                            </svg>
                                        </div>
                                        <p class="text-sm font-medium mb-1" style="color: var(--color-primary-2);">Vista previa no disponible</p>
                                        <p class="text-xs mb-3" style="color: var(--color-secondary);">Este formato no puede visualizarse en el navegador</p>
                                        <a href="{{ route('files.show', ['path' => $currentFile['path']]) }}" download
                                            class="btn-primary" style="height: 36px; padding: 0 16px; font-size: 13px;">
                                            Descargar para ver
                                        </a>
                                    </div>
                                @endif
                            </div>
                        @else
                            <div class="flex-1 flex flex-col items-center justify-center text-center px-6">
                                <div class="w-14 h-14 rounded-2xl flex items-center justify-center mb-3" style="background-color: var(--color-icon-bg);">
                                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color: var(--color-secondary);">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                </div>
                                <p class="text-sm font-medium" style="color: var(--color-secondary);">Todavía no hay ningún archivo para este documento.</p>
                            </div>
                        @endif
                    </div>

                </div>
            </div>
        </flux:modal>
    @endif
@endif
