{{-- Documentos informativos (admin_only) — cards estilo period-card, solo lectura --}}
@if($informativeDocuments->count() > 0)
    <div class="space-y-3">
        <div class="px-1">
            <p class="font-bold text-[var(--color-primary-2)] truncate" style="font-size: 20px;">Informativos</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5">
            @foreach($informativeDocuments as $document)
                @php $adminFiles = $this->getFileToDisplay($document); @endphp

                <div wire:key="informative-doc-{{ $document->id }}" class="period-card group">
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

                        @if(count($adminFiles) > 0)
                            <button wire:click="openDocumentViewer({{ $document->id }})"
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
            @endforeach
        </div>
    </div>
@endif
