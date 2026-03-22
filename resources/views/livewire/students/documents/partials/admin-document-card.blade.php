{{-- Partial reutilizable para cada card de admin-only --}}
@php
    $showAdminFiles = $this->shouldShowAdminFiles($document);
    $adminFiles     = $this->getFileToDisplay($document);
@endphp

<div class="rounded-lg  border border-[var(--color-card-bg)] hover:border-[var(--color-border-hover)] transition-all px-3 py-4">
    <div class="flex items-start gap-3">

        <div class="w-8 h-8 rounded-md bg-[var(--color-icon-bg)] flex items-center justify-center flex-shrink-0 mt-0.5">
            <i class="fas fa-file-alt text-[var(--color-icon)] text-sm"></i>
        </div>

        <div class="flex-1 min-w-0">
            <h4 class="font-medium text-[var(--color-primary-2)] text-sm truncate">{{ $document->name }}</h4>

            @if($document->file && ($document->file->firman || $document->file->observations))
                <div x-data="{ open: false }">
                    <button @click.stop="open = !open"
                            class="text-xs text-[var(--color-secondary)] hover:text-[var(--color-text)] flex items-center gap-1 transition-colors mt-0.5 focus:outline-none">
                        <i class="fas fa-info-circle text-[10px]"></i>
                        <span>Información adicional</span>
                        <i class="fas fa-chevron-down text-[10px] transition-transform" :class="{ 'rotate-180': open }"></i>
                    </button>
                    <div x-show="open" x-collapse class="mt-2 space-y-2">
                        @if($document->file->firman)
                            <div class="rounded p-2.5">
                                <p class="text-xs font-semibold text-[var(--color-secondary)] mb-1">
                                    <i class="fas fa-signature mr-1"></i>Firman:
                                </p>
                                <p class="text-xs text-[var(--color-secondary)] whitespace-pre-line">{{ $document->file->firman }}</p>
                            </div>
                        @endif
                        @if($document->file->observations)
                            <div class="rounded p-2.5">
                                <p class="text-xs font-semibold text-[var(--color-secondary)] mb-1">
                                    <i class="fas fa-info-circle mr-1"></i>Observaciones:
                                </p>
                                <p class="text-xs text-[var(--color-secondary)] whitespace-pre-line">{{ $document->file->observations }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            @endif
        </div>

        <div class="flex-shrink-0 flex items-center gap-1.5 mt-0.5">
            @if($showAdminFiles && $adminFiles)
                @foreach($adminFiles as $file)
                    @php $isPdf = str_ends_with(strtolower($file['path']), '.pdf'); @endphp
                    <div class="relative group">
                        <button wire:click="previewFile('{{ $file['path'] }}','{{ $file['name'] }}')"
                                class="w-7 h-7 flex items-center justify-center rounded-md transition text-xs
                                       bg-[var(--color-icon-bg)] text-[var(--color-icon)]
                                       hover:bg-[var(--color-icon-bg-hover)] hover:text-[var(--color-icon-hover)]">
                            <i class="fas {{ $isPdf ? 'fa-file-pdf' : 'fa-file-word' }} text-xs"></i>
                        </button>
                        <div class="absolute bottom-full mb-1.5 left-1/2 -translate-x-1/2 px-2 py-1 text-xs rounded
                                    opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap
                                    bg-[var(--color-icon-bg-hover)] text-[var(--color-icon-hover)] pointer-events-none">
                            {{ $isPdf ? 'Ver PDF' : 'Ver Word' }}
                        </div>
                    </div>
                @endforeach
            @endif

            @if(!empty(trim($document->comments ?? '')))
                <div class="relative group">
                    <button wire:click="openComments({{ $document->id }})"
                            class="w-7 h-7 flex items-center justify-center rounded-md transition text-xs
                                   bg-[var(--color-icon-bg)] text-[var(--color-icon)]
                                   hover:bg-[var(--color-icon-bg-hover)] hover:text-[var(--color-icon-hover)]">
                        <i class="fas fa-comment-dots text-xs"></i>
                    </button>
                    <div class="absolute bottom-full mb-1.5 left-1/2 -translate-x-1/2 px-2 py-1 text-xs rounded
                                opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap
                                bg-[var(--color-icon-bg-hover)] text-[var(--color-icon-hover)] pointer-events-none">
                        Comentarios
                    </div>
                </div>
            @endif
        </div>

    </div>
</div>