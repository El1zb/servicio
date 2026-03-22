{{-- Document Item — estilo Admin Card --}}
{{-- Variables esperadas: $document, $isExpired, $student --}}
@php
    $canUpload      = $this->canUploadFile($document);
    $showAdminFiles = $this->shouldShowAdminFiles($document);
    $adminFiles     = $this->getFileToDisplay($document);
    $hasFile        = (bool) $document->student_file_name;
    $statusConfig   = [
        'revisado'    => ['label' => 'Revisado',    'dot' => 'bg-green-500',  'text' => 'text-green-500'],
        'rechazado'   => ['label' => 'Rechazado',   'dot' => 'bg-red-500',    'text' => 'text-red-500'],
        'en_revision' => ['label' => 'En revisión', 'dot' => 'bg-blue-500',   'text' => 'text-blue-500'],
    ];
    $status = $statusConfig[$document->status] ?? null;
@endphp

<div class="rounded-lg  border border-[var(--color-card-bg)] hover:border-[var(--color-border-hover)] transition-all px-3 py-4">
    <div class="flex items-start gap-3">

        {{-- Ícono --}}
        <div class="w-8 h-8 rounded-md bg-[var(--color-icon-bg)] flex items-center justify-center flex-shrink-0 mt-0.5">
            <i class="fas fa-file-alt text-[var(--color-icon)] text-sm"></i>
        </div>

        {{-- Nombre + subtítulo + badge + info adicional --}}
        <div class="flex-1 min-w-0">
            <h4 class="font-medium text-[var(--color-primary-2)] text-sm truncate">{{ $document->name }}</h4>

            {{-- Nombre de archivo / Sin entregar + badge de estado inline --}}
            <div class="flex items-center gap-2 mt-0.5 flex-wrap">
                @if($hasFile)
                    @php
                        $nameParts = explode('_'.$student->control_number, $document->student_file_name);
                        $baseName  = $nameParts[0];
                        $ext       = pathinfo($document->student_file_name, PATHINFO_EXTENSION);
                    @endphp
                    <span class="text-xs text-[var(--color-secondary)] truncate">{{ $baseName }}.{{ $ext }}</span>

                    {{-- Badge inline junto al nombre del archivo --}}
                    @if($canUpload && $status)
                        <div class="flex items-center gap-1">
                            <div class="w-1.5 h-1.5 rounded-full {{ $status['dot'] }} flex-shrink-0"></div>
                            <span class="text-[11px] font-semibold {{ $status['text'] }}">{{ $status['label'] }}</span>
                        </div>
                    @endif
                @else
                    <span class="text-xs text-[var(--color-secondary)] italic">Sin entregar</span>
                    @if($canUpload && $document->file && !$isExpired)
                        <span class="text-[10px] text-[var(--color-secondary)] hidden sm:inline">
                            · Máx. {{ $this->formatSize($document->file->max_size * 1024) }}
                        </span>
                    @endif
                @endif
            </div>

            {{-- Información adicional colapsable --}}
            @if($document->file && ($document->file->firman || $document->file->observations))
                <div x-data="{ open: false }" class="mt-1">
                    <button @click.stop="open = !open"
                            class="inline-flex items-center gap-1 text-[11px] text-[var(--color-secondary)] hover:text-[var(--color-text)] transition-colors focus:outline-none">
                        <i class="fas fa-info-circle text-[10px]"></i>
                        <span>Información adicional</span>
                        <i class="fas fa-chevron-down text-[9px] transition-transform duration-200" :class="{ 'rotate-180': open }"></i>
                    </button>
                    <div x-show="open" x-collapse class="mt-1.5 pl-1 border-l-2 border-[var(--color-border-hover)] space-y-1.5">
                        @if($document->file->firman)
                            <div>
                                <p class="text-[10px] font-semibold text-[var(--color-secondary)] uppercase tracking-wide mb-0.5">
                                    <i class="fas fa-signature mr-1"></i>Firman
                                </p>
                                <p class="text-xs text-[var(--color-secondary)] whitespace-pre-line">{{ $document->file->firman }}</p>
                            </div>
                        @endif
                        @if($document->file->observations)
                            <div>
                                <p class="text-[10px] font-semibold text-[var(--color-secondary)] uppercase tracking-wide mb-0.5">
                                    <i class="fas fa-info-circle mr-1"></i>Observaciones
                                </p>
                                <p class="text-xs text-[var(--color-secondary)] whitespace-pre-line">{{ $document->file->observations }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            @endif
        </div>

        {{-- Columna derecha — solo acciones --}}
        <div class="flex-shrink-0 flex items-center gap-1.5 mt-0.5">

            {{-- Archivos del admin — SIEMPRE visibles --}}
            @if($adminFiles)
                @foreach($adminFiles as $file)
                    @php $isPdf = str_ends_with(strtolower($file['path']), '.pdf'); @endphp
                    <div class="relative group">
                        <button wire:click="previewFile('{{ $file['path'] }}','{{ $file['name'] }}')"
                                class="w-7 h-7 flex items-center justify-center rounded-md transition text-xs
                                       bg-[var(--color-icon-bg)] text-[var(--color-icon)]
                                       hover:bg-[var(--color-icon-bg-hover)] hover:text-[var(--color-icon-hover)]">
                            <i class="fas {{ $isPdf ? 'fa-file-pdf' : ($file['type']==='individual' ? 'fa-file' : 'fa-file-word') }} text-xs"></i>
                        </button>
                        <div class="absolute bottom-full mb-1.5 left-1/2 -translate-x-1/2 px-2 py-1 text-xs rounded
                                    opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap pointer-events-none
                                    bg-[var(--color-icon-bg-hover)] text-[var(--color-icon-hover)]">
                            {{ $isPdf ? 'Ver PDF' : ($file['type']==='individual' ? 'Ver archivo' : 'Ver Word') }}
                        </div>
                    </div>
                @endforeach
            @endif

            {{-- Ver mi archivo --}}
            @if($canUpload && $document->student_file_path)
                <div class="relative group">
                    <button wire:click="previewFile('{{ $document->student_file_path }}','{{ $document->student_file_name }}')"
                            class="w-7 h-7 flex items-center justify-center rounded-md transition text-xs
                                   bg-[var(--color-icon-bg)] text-[var(--color-icon)]
                                   hover:bg-[var(--color-icon-bg-hover)] hover:text-[var(--color-icon-hover)]">
                        <i class="fas fa-eye text-xs"></i>
                    </button>
                    <div class="absolute bottom-full mb-1.5 left-1/2 -translate-x-1/2 px-2 py-1 text-xs rounded
                                opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap pointer-events-none
                                bg-[var(--color-icon-bg-hover)] text-[var(--color-icon-hover)]">
                        Ver mi archivo
                    </div>
                </div>
            @endif

            {{-- Subir (sin archivo) / Reemplazar (con archivo, solo si NO venció) --}}
            @if($canUpload && !$isExpired)
                <div class="relative group">
                    <button type="button"
                            onclick="document.getElementById('fileInput-{{ $document->id }}').click()"
                            class="w-7 h-7 flex items-center justify-center rounded-md transition text-xs cursor-pointer
                                   bg-[var(--color-icon-bg)] text-[var(--color-icon)]
                                   hover:bg-[var(--color-icon-bg-hover)] hover:text-[var(--color-icon-hover)]">
                        <i class="fas {{ $hasFile ? 'fa-sync-alt' : 'fa-upload' }} text-xs"></i>
                    </button>
                    <div class="absolute bottom-full mb-1.5 left-1/2 -translate-x-1/2 px-2 py-1 text-xs rounded
                                opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap pointer-events-none
                                bg-[var(--color-icon-bg-hover)] text-[var(--color-icon-hover)]">
                        {{ $hasFile ? 'Reemplazar' : 'Subir archivo' }}
                    </div>
                </div>
                <input type="file" id="fileInput-{{ $document->id }}" class="hidden"
                       wire:model="fileUpload.{{ $document->id }}" accept=".pdf">
            @endif

            {{-- Cancelar entrega --}}
            @if($canUpload && $hasFile && $document->status !== 'revisado' && !$isExpired)
                <div class="relative group">
                    <button type="button"
                            wire:click="cancelUpload({{ $document->id }})"
                            wire:confirm="¿Seguro que deseas cancelar la entrega de '{{ $document->name }}'?"
                            class="w-7 h-7 flex items-center justify-center rounded-md transition text-xs
                                   text-red-400 hover:bg-red-50 hover:text-red-600 cursor-pointer">
                        <i class="fas fa-trash-alt text-xs"></i>
                    </button>
                    <div class="absolute bottom-full mb-1.5 left-1/2 -translate-x-1/2 px-2 py-1 text-xs rounded
                                opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap pointer-events-none
                                bg-[var(--color-icon-bg-hover)] text-[var(--color-icon-hover)]">
                        Cancelar entrega
                    </div>
                </div>
            @endif

            {{-- Comentarios --}}
            @if(!empty(trim($document->comments ?? '')))
                <div class="relative group">
                    <button wire:click="openComments({{ $document->id }})"
                            class="w-7 h-7 flex items-center justify-center rounded-md transition text-xs
                                   bg-[var(--color-icon-bg)] text-[var(--color-icon)]
                                   hover:bg-[var(--color-icon-bg-hover)] hover:text-[var(--color-icon-hover)]">
                        <i class="fas fa-comment-dots text-xs"></i>
                    </button>
                    <div class="absolute bottom-full mb-1.5 left-1/2 -translate-x-1/2 px-2 py-1 text-xs rounded
                                opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap pointer-events-none
                                bg-[var(--color-icon-bg-hover)] text-[var(--color-icon-hover)]">
                        Comentarios
                    </div>
                </div>
            @endif

        </div>
    </div>
</div>