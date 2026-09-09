{{-- Pestaña "Informativos": documentos que solo publica el admin (admin_only),
     de solo lectura. El título lo aporta el switcher. --}}
<div class="space-y-3">
    @if($informativeDocuments->count() > 0)
        <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-3 sm:gap-5">
            @foreach($informativeDocuments as $document)
                @php
                    $adminFiles = $document->filesToDisplay();
                    $canView    = count($adminFiles) > 0;
                    $tag        = $canView ? 'button' : 'div';
                @endphp

                {{-- Desktop: se conserva el botón de acción tal cual, pero además
                     toda la card abre el visor de un clic (el botón detiene la
                     propagación para no disparar los dos). --}}
                <div wire:key="informative-doc-{{ $document->id }}"
                    @if($canView) wire:click="openDocumentViewer({{ $document->id }})" @endif
                    class="period-card document-card-desktop group {{ $canView ? 'period-card--clickable' : '' }}">
                    <div class="stat-card-top">
                        <div class="min-w-0 flex-1">
                            <p class="document-card-title">{{ $document->name }}</p>
                        </div>
                        <div class="stat-card-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <path d="M9 13H13M9 17H15M13.0714 2.52927V6.83334C13.0714 7.93791 13.9668 8.83334 15.0714 8.83334H19.3494M13.0714 2.52927C12.7307 2.5095 12.3738 2.5 12 2.5C6.26471 2.5 4.5 4.73529 4.5 12C4.5 19.2647 6.26471 21.5 12 21.5C17.7353 21.5 19.5 19.2647 19.5 12C19.5 10.8146 19.453 9.76307 19.3494 8.83334M13.0714 2.52927L19.3494 8.83334" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-1 mt-auto">
                        @if($canView)
                            <button wire:click.stop="openDocumentViewer({{ $document->id }})"
                                    title="Ver documento"
                                    class="w-7 h-7 flex items-center justify-center rounded-full flex-shrink-0
                                           text-[var(--color-icon)] bg-transparent cursor-pointer
                                           opacity-0 group-hover:opacity-100
                                           transition-all duration-150
                                           hover:text-[var(--color-icon-hover)] hover:bg-[var(--sidebar-color-hover)]">
                                <svg width="13" height="13" viewBox="0 0 16 16" fill="currentColor">
                                    <path d="M7.8557,3.65731A7.00442,7.00442,0,0,0,1,8.00054a7.58806,7.58806,0,0,0,7.14647,4.34215A7.00224,7.00224,0,0,0,15,8.00054,7.586,7.586,0,0,0,7.8557,3.65731M6.65709,10.94592a5.10784,5.10784,0,0,1-4.214-2.94538s.66446-2.58462,4.32923-3.03692A2.786,2.786,0,0,0,5.35939,6.187L8.14647,7.40715H4.97709a3.46976,3.46976,0,0,0-.05277.57616,3.34816,3.34816,0,0,0,1.73384,2.96154m2.84954.01938a3.3991,3.3991,0,0,0,.10768-5.90692,5.00551,5.00551,0,0,1,3.94155,2.94323s-.60307,2.44138-4.04923,2.96369"/>
                                </svg>
                            </button>
                        @else
                            <span class="text-xs italic" style="color: var(--color-secondary);">Sin archivo</span>
                        @endif
                    </div>
                </div>

                {{-- Mobile: card completa clicable, sin botones — un tap abre el visor. --}}
                <{{ $tag }} @if($canView) type="button" wire:click="openDocumentViewer({{ $document->id }})" @endif
                    class="document-mobile-card {{ $canView ? '' : 'document-mobile-card--static' }}">
                    <p class="document-card-title">{{ $document->name }}</p>
                    <p class="stat-card-description" style="margin:0;">
                        {{ $canView ? 'Ver documento' : 'Sin archivo' }}
                    </p>
                </{{ $tag }}>
            @endforeach
        </div>
    @else
        <div class="text-center py-20">
            <svg class="w-14 h-14 mx-auto mb-4" style="color: var(--color-secondary);" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            <h3 class="text-lg font-bold mb-2" style="color: var(--color-primary-2);">
                @if($searchDocuments !== '') No se encontraron documentos @else Aún no hay documentos informativos @endif
            </h3>
            <p class="mb-6 max-w-sm mx-auto text-sm" style="color: var(--color-secondary);">
                @if($searchDocuments !== '')
                    Ajusta el buscador para ver otros resultados.
                @else
                    Aquí aparecerán los formatos, guías y avisos que publique el administrador.
                @endif
            </p>
        </div>
    @endif
</div>
