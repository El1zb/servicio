{{-- Documentos Informativos (admin_only) --}}
@if($adminOnlyDocuments->count() > 0)
<div x-data="{ expanded: false }" class="mb-4">

    {{-- Header de sección --}}
    <button @click="expanded = !expanded"
            class="w-full flex items-center justify-between gap-2 px-1 mb-7 group focus:outline-none focus:ring-0">
        <div class="flex items-center gap-2">
            <div class="w-1 h-4 rounded-full bg-[var(--color-indicator)]"></div>
            <p class="text-xs font-semibold text-[var(-color-text)] uppercase tracking-wider" style="font-weight: bold;">
                Información y Recursos
            </p>
            <span class="text-xs text-[var(--color-secondary)] opacity-80">·&nbsp; {{ $adminOnlyDocuments->count() }}</span>
        </div>
        @if($adminOnlyDocuments->count() > 1)
            <div class="flex items-center gap-1 text-xs text-[var(--color-secondary)] group-hover:text-[var(--color-text)] transition-colors duration-200">
                <span x-text="expanded ? 'VER MENOS' : 'VER TODOS'" style="font-weight: bold;"></span>
                <i class="fas fa-chevron-down text-[10px] transition-transform duration-200" :class="{ 'rotate-180': expanded }"></i>
            </div>
        @endif
    </button>

    <div class="space-y-2">
        {{-- Siempre visible: el primero --}}
        @php $first = $adminOnlyDocuments->first(); @endphp
        @php
            $showAdminFiles = $this->shouldShowAdminFiles($first);
            $adminFiles     = $this->getFileToDisplay($first);
        @endphp
        <div class="bg-[var(--color-card-bg)] rounded-lg border border-[var(--color-card-bg)] hover:border-[var(--color-border-hover)] transition-all p-3">
            <div class="flex items-center gap-3">
                <div class="w-7 h-7 rounded-md bg-[var(--color-icon-bg)] flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-file-alt text-[var(--color-icon)] text-xs"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <h4 class="font-medium text-[var(--index-text-primary)] text-sm truncate" style="font-weight: var(--font-weight-medium);">{{ $first->name }}</h4>
                </div>
                <div class="flex-shrink-0 flex items-center gap-1.5">
                    @if($showAdminFiles && $adminFiles)
                        @foreach($adminFiles as $file)
                            @php $isPdf = str_ends_with(strtolower($file['path']), '.pdf'); @endphp
                            <div class="relative group">
                                <button wire:click="previewFile('{{ $file['path'] }}','{{ $file['name'] }}')"
                                        class="w-7 h-7 flex items-center justify-center rounded-md transition text-xs
                                        {{ $isPdf
                                            ? 'bg-[var(--color-icon-bg)] text-[var(--color-icon)] hover:bg-[var(--color-icon-bg-hover)] hover:text-[var(--color-icon-hover)]'
                                            : 'bg-[var(--color-icon-bg)] text-[var(--color-icon)] hover:bg-[var(--color-icon-bg-hover)] hover:text-[var(--color-icon-hover)]'}}">
                                    <i class="fas {{ $isPdf ? 'fa-file-pdf' : 'fa-file-word' }} text-xs"></i>
                                </button>
                                <div class="absolute bottom-full mb-1.5 left-1/2 -translate-x-1/2 px-2 py-1 text-xs rounded
                                            opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap
                                            bg-[var(--color-icon-bg-hover)] text-[var(--color-icon-hover)]">
                                    {{ $isPdf ? 'Ver PDF' : 'Ver Word' }}
                                </div>
                            </div>
                        @endforeach
                    @endif
                    @if(!empty(trim($first->comments ?? '')))
                        <div class="relative group">
                            <button wire:click="openComments({{ $first->id }})"
                                    class="w-7 h-7 flex items-center justify-center rounded-md transition text-xs
                                        bg-[var(--color-icon-bg)] text-[var(--color-icon)] hover:bg-[var(--color-icon-bg-hover)] hover:text-[var(--color-icon-hover)]">
                                <i class="fas fa-comment-dots text-xs"></i>
                            </button>
                            <div class="absolute bottom-full mb-1.5 left-1/2 -translate-x-1/2 px-2 py-1 text-xs rounded
                                        opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap
                                        bg-[var(--color-icon-bg-hover)] text-[var(--color-icon-hover)]">
                                Comentarios
                            </div>
                        </div>
                    @endif
                </div>
            </div>
            @if($first->file && ($first->file->firman || $first->file->observations))
                <div x-data="{ open: false }" class="mt-2">
                    <button @click="open = !open"
                            class="text-xs text-[var(--color-secondary)] hover:text-[var(--color-text)] flex items-center gap-1 transition-colors">
                        <i class="fas fa-info-circle text-[var(--color-secondary)]"></i>
                        <span>Información adicional</span>
                        <i class="fas fa-chevron-down text-[10px] transition-transform" :class="{ 'rotate-180': open }"></i>
                    </button>
                    <div x-show="open" x-collapse class="mt-2 space-y-2">
                        @if($first->file->firman)
                            <div class=" rounded p-2.5">
                                <p class="text-xs font-semibold text-[var(--index-text-secondary)] mb-1"><i class="fas fa-signature mr-1"></i>Firman:</p>
                                <p class="text-xs text-[var(--index-text-primary)] whitespace-pre-line">{{ $first->file->firman }}</p>
                            </div>
                        @endif
                        @if($first->file->observations)
                            <div class=" rounded p-2.5">
                                <p class="text-xs font-semibold text-[var(--index-text-secondary)] mb-1"><i class="fas fa-info-circle mr-1"></i>Observaciones:</p>
                                <p class="text-xs text-[var(--index-text-primary)] whitespace-pre-line">{{ $first->file->observations }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            @endif
        </div>

        {{-- El resto colapsable --}}
        @if($adminOnlyDocuments->count() > 1)
            <div x-show="expanded" x-collapse>
                <div class="space-y-2">
                    @foreach($adminOnlyDocuments->skip(1) as $document)
                        @php
                            $showAdminFiles = $this->shouldShowAdminFiles($document);
                            $adminFiles     = $this->getFileToDisplay($document);
                        @endphp
                        <div class="bg-[var(--index-content-bg)] rounded-lg border border-[var(--index-border)] hover:border-[var(--index-accent)] transition-all p-3">
                            <div class="flex items-center gap-3">
                                <div class="w-7 h-7 rounded-md bg-[var(--period-detail-card-indicator-bg)] flex items-center justify-center flex-shrink-0">
                                    <i class="fas fa-file-alt text-[var(--index-text-secondary)] text-xs"></i>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h4 class="font-medium text-[var(--index-text-primary)] text-sm truncate">{{ $document->name }}</h4>
                                </div>
                                <div class="flex-shrink-0 flex items-center gap-1.5">
                                    @if($showAdminFiles && $adminFiles)
                                        @foreach($adminFiles as $file)
                                            @php $isPdf = str_ends_with(strtolower($file['path']), '.pdf'); @endphp
                                            <div class="relative group">
                                                <button wire:click="previewFile('{{ $file['path'] }}','{{ $file['name'] }}')"
                                                        class="w-7 h-7 flex items-center justify-center rounded-md transition text-xs
                                                        {{ $isPdf
                                                            ? 'bg-[var(--period-detail-btn-pdf-bg)] text-[var(--period-detail-btn-pdf-text)] hover:bg-[var(--period-detail-btn-pdf-hover)]'
                                                            : 'bg-[var(--period-detail-btn-word-bg)] text-[var(--period-detail-btn-word-text)] hover:bg-[var(--period-detail-btn-word-hover)]' }}">
                                                    <i class="fas {{ $isPdf ? 'fa-file-pdf' : 'fa-file-word' }} text-xs"></i>
                                                </button>
                                                <div class="absolute bottom-full mb-1.5 left-1/2 -translate-x-1/2 px-2 py-1 text-xs rounded
                                                            opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap
                                                            bg-[var(--index-text-primary)] text-[var(--index-card-bg)]">
                                                    {{ $isPdf ? 'Ver PDF' : 'Ver Word' }}
                                                </div>
                                            </div>
                                        @endforeach
                                    @endif
                                    @if(!empty(trim($document->comments ?? '')))
                                        <button wire:click="openComments({{ $document->id }})"
                                                class="w-7 h-7 flex items-center justify-center rounded-md transition
                                                       text-[var(--index-accent)] hover:bg-[var(--index-accent)]/10 text-xs">
                                            <i class="fas fa-comment-dots"></i>
                                        </button>
                                    @endif
                                </div>
                            </div>
                            @if($document->file && ($document->file->firman || $document->file->observations))
                                <div x-data="{ open: false }" class="mt-2">
                                    <button @click="open = !open"
                                            class="text-xs text-[var(--index-text-secondary)] hover:text-[var(--index-text-primary)] flex items-center gap-1 transition-colors">
                                        <i class="fas fa-info-circle text-[var(--index-accent)]"></i>
                                        <span>Información adicional</span>
                                        <i class="fas fa-chevron-down text-[10px] transition-transform" :class="{ 'rotate-180': open }"></i>
                                    </button>
                                    <div x-show="open" x-collapse class="mt-2 space-y-2">
                                        @if($document->file->firman)
                                            <div class="bg-[var(--period-detail-expanded-bg)] rounded p-2.5">
                                                <p class="text-xs font-semibold text-[var(--index-text-primary)] mb-1"><i class="fas fa-signature mr-1"></i>Firman:</p>
                                                <p class="text-xs text-[var(--index-text-secondary)] whitespace-pre-line">{{ $document->file->firman }}</p>
                                            </div>
                                        @endif
                                        @if($document->file->observations)
                                            <div class="bg-[var(--period-detail-expanded-bg)] rounded p-2.5">
                                                <p class="text-xs font-semibold text-[var(--index-text-primary)] mb-1"><i class="fas fa-info-circle mr-1"></i>Observaciones:</p>
                                                <p class="text-xs text-[var(--index-text-secondary)] whitespace-pre-line">{{ $document->file->observations }}</p>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>

    <div class="mt-4 border-t border-[var(--index-border)]"></div>
</div>
@endif