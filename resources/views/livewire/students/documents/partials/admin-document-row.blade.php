{{-- admin-document-row --}}
{{-- Variables esperadas: $document --}}
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
                        <p class="text-xs font-semibold text-[var(--index-text-secondary)] mb-1">
                            <i class="fas fa-signature mr-1"></i>Firman:
                        </p>
                        <p class="text-xs text-[var(--index-text-primary)] whitespace-pre-line">{{ $document->file->firman }}</p>
                    </div>
                @endif
                @if($document->file->observations)
                    <div class="bg-[var(--period-detail-expanded-bg)] rounded p-2.5">
                        <p class="text-xs font-semibold text-[var(--index-text-secondary)] mb-1">
                            <i class="fas fa-info-circle mr-1"></i>Observaciones:
                        </p>
                        <p class="text-xs text-[var(--index-text-primary)] whitespace-pre-line">{{ $document->file->observations }}</p>
                    </div>
                @endif
            </div>
        </div>
    @endif
</div>