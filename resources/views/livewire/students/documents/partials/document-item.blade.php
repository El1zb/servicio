{{-- Document Item --}}
{{-- Variables esperadas: $document, $isExpired, $student --}}
@php
    $canUpload      = $this->canUploadFile($document);
    $showAdminFiles = $this->shouldShowAdminFiles($document);
    $adminFiles     = $this->getFileToDisplay($document);
    $statusNames    = ['revisado' => 'Revisado', 'rechazado' => 'Rechazado', 'en_revision' => 'En revisión'];
@endphp

<div class="bg-[var(--index-content-bg)] rounded-lg border border-[var(--index-border)] hover:border-[var(--index-accent)] transition-all p-4">
    <div class="flex items-center gap-4">

        {{-- Info --}}
        <div class="flex-1 min-w-0">
            <h4 class="font-semibold text-[var(--index-text-primary)] text-sm truncate">{{ $document->name }}</h4>
            @if($document->student_file_name)
                @php
                    $nameParts = explode('_'.$student->control_number, $document->student_file_name);
                    $baseName  = $nameParts[0];
                    $ext       = pathinfo($document->student_file_name, PATHINFO_EXTENSION);
                @endphp
                <p class="text-xs text-[var(--index-text-secondary)] truncate">{{ $baseName }}.{{ $ext }}</p>
            @else
                <p class="text-xs text-[var(--index-text-secondary)] italic">Sin entregar</p>
            @endif
        </div>

        {{-- Status badge --}}
        @if($document->student_file_name && $canUpload)
            <span class="flex-shrink-0 px-2.5 py-1 rounded-full text-xs font-bold
                {{ $document->status === 'revisado'    ? 'bg-[var(--index-status-approved-bg)] text-[var(--index-status-approved-icon)]' : '' }}
                {{ $document->status === 'rechazado'   ? 'bg-[var(--index-status-rejected-bg)] text-[var(--index-status-rejected-icon)]' : '' }}
                {{ $document->status === 'en_revision' ? 'bg-[var(--index-status-normal-bg)] text-[var(--index-status-normal-icon)]'     : '' }}">
                {{ $statusNames[$document->status] ?? ucfirst($document->status) }}
            </span>
        @endif

        {{-- Acciones --}}
        <div class="flex-shrink-0 flex items-center gap-2">

            {{-- Archivos del admin --}}
            @if($showAdminFiles && $adminFiles)
                <div class="flex gap-1">
                    @foreach($adminFiles as $file)
                        @php $isPdf = str_ends_with(strtolower($file['path']), '.pdf'); @endphp
                        <div class="relative group">
                            <button wire:click="previewFile('{{ $file['path'] }}','{{ $file['name'] }}')"
                                    class="w-8 h-8 flex items-center justify-center rounded-lg transition text-xs
                                    {{ $isExpired
                                        ? 'bg-[var(--period-detail-btn-cancel-bg)] text-[var(--index-text-secondary)] cursor-not-allowed'
                                        : ($isPdf
                                            ? 'bg-[var(--period-detail-btn-pdf-bg)] text-[var(--period-detail-btn-pdf-text)] hover:bg-[var(--period-detail-btn-pdf-hover)]'
                                            : 'bg-[var(--period-detail-btn-word-bg)] text-[var(--period-detail-btn-word-text)] hover:bg-[var(--period-detail-btn-word-hover)]') }}"
                                    {{ $isExpired ? 'disabled' : '' }}>
                                <i class="fas {{ $isPdf ? 'fa-file-pdf' : ($file['type']==='individual' ? 'fa-file' : 'fa-file-word') }}"></i>
                            </button>
                            <div class="absolute bottom-full mb-2 left-1/2 -translate-x-1/2 px-2 py-1 text-xs rounded
                                        opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap
                                        {{ $isExpired ? 'bg-[var(--index-text-secondary)] text-white' : ($isPdf ? 'bg-[var(--index-brand-primary)] text-white' : 'bg-[var(--index-brand-secondary)] text-white') }}">
                                {{ $isPdf ? 'Ver PDF' : ($file['type']==='individual' ? 'Ver archivo asignado' : 'Ver Word') }}
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

            {{-- Ver mi archivo --}}
            @if($canUpload && $document->student_file_path)
                <div class="relative group">
                    <button wire:click="previewFile('{{ $document->student_file_path }}','{{ $document->student_file_name }}')"
                            class="w-8 h-8 flex items-center justify-center rounded-lg bg-[var(--period-detail-btn-bg-g)] text-[var(--period-detail-btn-text-g)] hover:bg-[var(--period-detail-btn-hover-g)] transition text-xs cursor-pointer">
                        <i class="fas fa-eye"></i>
                    </button>
                    <div class="absolute bottom-full mb-2 left-1/2 -translate-x-1/2 px-2 py-1 text-xs rounded
                                opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap bg-[var(--index-brand-primary)] text-white">
                        Ver mi archivo
                    </div>
                </div>
            @endif

            {{-- Subir / Reemplazar --}}
            @if($canUpload)
                <div class="relative group">
                    <button type="button"
                            onclick="document.getElementById('fileInput-{{ $document->id }}').click()"
                            class="w-8 h-8 flex items-center justify-center rounded-lg transition text-xs
                            {{ $isExpired
                                ? 'bg-[var(--period-detail-btn-cancel-bg)] text-[var(--index-text-secondary)] cursor-not-allowed'
                                : ($document->student_file_name
                                    ? 'bg-[var(--period-detail-btn-edit-bg)] text-[var(--period-detail-btn-edit-text)] hover:bg-[var(--period-detail-btn-edit-hover)] cursor-pointer'
                                    : 'bg-[var(--index-btn-primary-bg)] text-[var(--index-btn-primary-text)] hover:bg-[var(--index-btn-primary-hover)] cursor-pointer') }}"
                            {{ $isExpired ? 'disabled' : '' }}>
                        <i class="fas {{ $document->student_file_name ? 'fa-sync-alt' : 'fa-upload' }}"></i>
                    </button>
                    <div class="absolute bottom-full mb-2 left-1/2 -translate-x-1/2 px-2 py-1 text-xs rounded
                                opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap
                                {{ $isExpired ? 'bg-[var(--index-text-secondary)] text-white' : ($document->student_file_name ? 'bg-[var(--index-brand-primary)] text-white' : 'bg-[var(--index-btn-primary-bg)] text-white') }}">
                        {{ $document->student_file_name ? 'Reemplazar' : 'Subir archivo' }}
                    </div>
                </div>
                <input type="file" id="fileInput-{{ $document->id }}" class="hidden"
                       wire:model="fileUpload.{{ $document->id }}" accept=".pdf">
            @endif

            {{-- Cancelar entrega --}}
            @if($canUpload && $document->student_file_name && $document->status !== 'revisado' && !$isExpired)
                <div class="relative group">
                    <button type="button"
                            wire:click="cancelUpload({{ $document->id }})"
                            wire:confirm="¿Seguro que deseas cancelar la entrega de '{{ $document->name }}'?"
                            class="w-8 h-8 flex items-center justify-center rounded-lg bg-[var(--index-status-rejected-bg)] text-[var(--index-status-rejected-icon)] hover:opacity-80 transition text-xs cursor-pointer">
                        <i class="fas fa-trash-alt"></i>
                    </button>
                    <div class="absolute bottom-full mb-2 left-1/2 -translate-x-1/2 px-2 py-1 text-xs rounded
                                opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap
                                bg-[var(--index-status-rejected-icon)] text-white pointer-events-none">
                        Cancelar entrega
                    </div>
                </div>
            @endif

        </div>
    </div>

    {{-- Tamaño máximo --}}
    @if($canUpload && $document->file)
        <div class="mt-3">
            <div class="flex items-center gap-1.5 text-xs text-[var(--index-text-secondary)]">
                <i class="fas fa-info-circle text-[var(--index-accent)]"></i>
                <span>Tamaño máximo:</span>
                <span class="font-bold text-[var(--index-accent)]">{{ $this->formatSize($document->file->max_size * 1024) }}</span>
            </div>
        </div>
    @endif

    {{-- Info adicional y comentarios --}}
    @if(($document->file && ($document->file->firman || $document->file->observations)) || !empty(trim($document->comments ?? '')))
        <div x-data="{ expanded: false }" class="mt-3">
            <div class="flex items-center justify-between gap-4">
                @if($document->file && ($document->file->firman || $document->file->observations))
                    <button @click="expanded = !expanded"
                            class="text-xs text-[var(--index-text-secondary)] hover:text-[var(--index-text-primary)] flex items-center gap-1 transition-colors">
                        <i class="fas fa-info-circle"></i>
                        <span>Información adicional</span>
                        <i class="fas fa-chevron-down text-[10px] transition-transform" :class="{ 'rotate-180': expanded }"></i>
                    </button>
                @else
                    <div></div>
                @endif
                @if(!empty(trim($document->comments ?? '')))
                    <button wire:click="openComments({{ $document->id }})"
                            class="flex items-center gap-2 text-[var(--index-accent)] hover:text-[var(--index-brand-primary)] text-xs font-medium transition-colors">
                        <i class="fas fa-comment-dots"></i>
                        <span class="hidden sm:inline">Ver comentarios</span>
                        <i class="fas fa-chevron-right text-[10px]"></i>
                    </button>
                @endif
            </div>
            @if($document->file && ($document->file->firman || $document->file->observations))
                <div x-show="expanded" x-collapse class="mt-3 space-y-3">
                    @if($document->file->firman)
                        <div class="bg-[var(--period-detail-expanded-bg)] rounded p-3">
                            <p class="text-xs font-semibold text-[var(--index-text-primary)] mb-1"><i class="fas fa-signature mr-1"></i>Firman:</p>
                            <p class="text-xs text-[var(--index-text-secondary)] whitespace-pre-line">{{ $document->file->firman }}</p>
                        </div>
                    @endif
                    @if($document->file->observations)
                        <div class="bg-[var(--period-detail-expanded-bg)] rounded p-3">
                            <p class="text-xs font-semibold text-[var(--index-text-primary)] mb-1"><i class="fas fa-info-circle mr-1"></i>Observaciones:</p>
                            <p class="text-xs text-[var(--index-text-secondary)] whitespace-pre-line">{{ $document->file->observations }}</p>
                        </div>
                    @endif
                </div>
            @endif
        </div>
    @endif
</div>