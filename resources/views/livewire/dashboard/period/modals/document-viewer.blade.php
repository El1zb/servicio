{{-- =================== VISOR DE DOCUMENTOS (pantalla completa) ===================
     Mismo "look" que el visor de la sección de estudiantes
     (students/documents/partials/modals/document-viewer.blade.php): dock
     inferior en mobile, columna de archivos en escritorio. Aquí solo hay
     Word/PDF de LA plantilla desde la que se abrió (nunca de otra). --}}
@if($isDocViewerOpen)
    @php $docViewerFile = $this->getDocViewerFile(); @endphp

    @if($docViewerFile)
        @php
            $docViewerFiles = $this->getDocViewerFiles($docViewerFile);
            $currentDocFile = collect($docViewerFiles)->firstWhere('path', $docViewerFilePath);
            $dvExt          = $currentDocFile ? strtoupper(pathinfo($currentDocFile['path'], PATHINFO_EXTENSION)) : null;
            $dvLabels       = ['word' => 'Word', 'pdf' => 'PDF'];
        @endphp

        <flux:modal
            wire:model="isDocViewerOpen"
            :dismissible="false"
            :closable="false"
            class="!p-0 !max-w-none !rounded-none !shadow-none w-screen h-screen !m-0 overflow-hidden !outline-none"
            style="outline: none; max-width: 100vw; max-height: 100vh; margin: 0; inset: 0;">

            <div class="flex flex-col"
                style="height: 100vh; background-color: var(--color-contenedor-view);"
                x-data
                @keydown.window="
                    const el = document.activeElement;
                    const typing = el && (el.tagName === 'INPUT' || el.tagName === 'TEXTAREA' || el.isContentEditable);
                    if ($wire.isDocViewerOpen && !typing) {
                        if ($event.key === 'ArrowLeft') {
                            const btn = document.querySelector('button[title=\'Archivo anterior\']');
                            if (btn && !btn.disabled) btn.click();
                        } else if ($event.key === 'ArrowRight') {
                            const btn = document.querySelector('button[title=\'Siguiente archivo\']');
                            if (btn && !btn.disabled) btn.click();
                        }
                    }
                ">

                {{-- ── Barra: cerrar/navegación + nombre · extensión + descargar ── --}}
                <div class="viewer-topbar flex items-center gap-3 px-4 sm:px-6 py-3 flex-shrink-0"
                    style="box-shadow: 0 1px 3px rgba(0,0,0,0.05); position: relative; z-index: 1; min-height: 58px;">

                    <div class="flex items-center gap-1.5 flex-shrink-0">
                        <button type="button" wire:click="closeDocViewer" class="review-icon-btn">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                        <button type="button" wire:click="docViewerNavigate('prev')" @disabled(!$this->docViewerHasPrev()) class="review-icon-btn viewer-nav-arrow" title="Archivo anterior">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 18l-6-6 6-6"/></svg>
                        </button>
                        <button type="button" wire:click="docViewerNavigate('next')" @disabled(!$this->docViewerHasNext()) class="review-icon-btn viewer-nav-arrow" title="Siguiente archivo">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 18l6-6-6-6"/></svg>
                        </button>
                    </div>

                    <h3 class="text-sm font-semibold truncate flex-1 min-w-0" style="color: var(--color-primary-2);">
                        {{ $docViewerFile->name }}
                        @if($dvExt)
                            <span style="color: var(--color-secondary); font-weight: 500;">· {{ $dvExt }}</span>
                        @endif
                    </h3>

                    @if($currentDocFile)
                        <a href="{{ route('files.show', ['path' => $currentDocFile['path']]) }}" download="{{ $currentDocFile['name'] }}"
                            class="review-icon-btn flex-shrink-0" title="Descargar">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                            </svg>
                        </a>
                    @endif
                </div>

                <div class="flex-1 flex overflow-hidden">

                    {{-- ── Columna izquierda: solo Word/PDF de esta plantilla.
                         En mobile no hay espacio para ella — el dock inferior
                         hace su función. ── --}}
                    <div class="hidden sm:flex flex-col overflow-hidden flex-shrink-0"
                        style="width: var(--sidebar-width); background-color: var(--sidebar-color-bg); box-shadow: 1px 0 3px rgba(0,0,0,0.04); position: relative; z-index: 1;">

                        <div class="flex-1 overflow-y-auto px-3 pt-3 pb-2 space-y-1">
                            @foreach($docViewerFiles as $file)
                                @php
                                    $fColor = $file['type'] === 'pdf' ? '#DC2626' : '#2563EB';
                                @endphp
                                <button type="button" wire:click="docViewerSelectFile('{{ $file['path'] }}')"
                                    class="review-nav-item {{ $docViewerFilePath === $file['path'] ? 'is-active' : '' }}">
                                    <span class="status-badge-dot flex-shrink-0" style="color: {{ $fColor }};"></span>
                                    <span class="truncate flex-1">{{ $dvLabels[$file['type']] ?? 'Archivo' }}</span>
                                </button>
                            @endforeach
                        </div>
                    </div>

                    {{-- ── Columna central: documento ── --}}
                    <div class="flex-1 flex flex-col overflow-hidden min-w-0 relative">

                        @if($currentDocFile)
                            @php $dvFileExt = strtolower(pathinfo($currentDocFile['path'], PATHINFO_EXTENSION)); @endphp
                            <div class="flex-1 min-h-0" style="background-color: var(--color-contenedor-view);">
                                @if($dvFileExt === 'pdf' || $dvFileExt === 'docx')
                                    @php
                                        // El Word se sirve convertido a PDF (LibreOffice, ver
                                        // DocxToPdfConverter) y usa exactamente el mismo render
                                        // de canvas que un PDF real — misma fidelidad, mismo look.
                                        $dvPdfUrl = route('files.show', array_filter([
                                            'path' => $currentDocFile['path'],
                                            'as'   => $dvFileExt === 'docx' ? 'pdf' : null,
                                        ]));
                                    @endphp
                                    <div wire:key="doc-viewer-pdf-{{ $currentDocFile['path'] }}"
                                        wire:ignore
                                        x-data
                                        x-init="renderReviewPdf(@js($dvPdfUrl), $el); initPdfPinchZoom($el)"
                                        class="pdf-render-target w-full h-full overflow-y-auto overflow-x-auto"
                                        style="background-color: var(--color-contenedor-view); padding: 24px;">
                                    </div>

                                    {{-- Solo escritorio: en mobile el pellizco (dos dedos) sobre el
                                         propio documento hace zoom sin tocar el resto de la pantalla. --}}
                                    <div class="hidden sm:flex absolute bottom-5 right-5 items-center gap-1 rounded-full px-1.5 py-1.5"
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
                                        <a href="{{ route('files.show', ['path' => $currentDocFile['path']]) }}" download
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

                {{-- ── Dock inferior (solo mobile): mismo liquid glass del nav
                     flotante (mobile-bottom-nav) — píldora, icono+etiqueta,
                     reemplaza a la columna lateral de archivos en pantallas
                     chicas. ── --}}
                @if(count($docViewerFiles) > 1)
                    @php
                        $dvGlass = 'border border-white/30 bg-linear-to-b from-[rgba(255,255,255,0.45)] to-[rgba(255,255,255,0.22)] shadow-[inset_0_1px_0_0_rgba(255,255,255,0.4),0_20px_45px_-15px_rgba(0,0,0,0.18)] backdrop-blur-[20px] backdrop-saturate-[180%] dark:border-white/8 dark:from-[rgba(23,23,23,0.45)] dark:to-[rgba(23,23,23,0.22)] dark:shadow-[inset_0_1px_0_0_rgba(255,255,255,0.06),0_20px_45px_-15px_rgba(0,0,0,0.5)]';
                    @endphp
                    <div class="fixed bottom-[calc(1rem+env(safe-area-inset-bottom))] left-1/2 -translate-x-1/2 z-20 flex items-center gap-2 sm:hidden">
                        <nav class="viewer-dock {{ $dvGlass }}" aria-label="Archivos del documento">
                            @foreach($docViewerFiles as $f)
                                @php $fActive = $docViewerFilePath === $f['path']; @endphp
                                <button type="button" wire:click="docViewerSelectFile('{{ $f['path'] }}')"
                                    class="viewer-dock-item {{ $fActive ? 'is-active' : '' }}">
                                    @include('livewire.students.documents.partials.modals.viewer-dock-icon', ['type' => $f['type'], 'active' => $fActive])
                                    <span class="text-[10px] font-medium leading-none whitespace-nowrap">{{ $dvLabels[$f['type']] ?? 'Archivo' }}</span>
                                </button>
                            @endforeach
                        </nav>
                    </div>
                @endif
            </div>
        </flux:modal>
    @endif
@endif
