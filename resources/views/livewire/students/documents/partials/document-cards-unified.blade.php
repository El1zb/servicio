{{--
    Cronograma de Entregas — Vista Unificada
--}}

@php
    use Carbon\Carbon;

    $adminDocs = collect();
    foreach ($adminOnlyDocuments as $doc) {
        $adminDocs->push([
            'document'  => $doc,
            'type'      => 'admin_only',
            'isExpired' => false,
            'limitDate' => $this->effectiveDatePublic($doc),
        ]);
    }

    $studentDocs = collect();
    foreach ($documents as $limitDate => $docs) {
        foreach ($docs as $doc) {
            $isExpired = $limitDate !== 'Sin fecha' && now()->gt(Carbon::parse($limitDate)->endOfDay());
            $studentDocs->push([
                'document'  => $doc,
                'type'      => 'student',
                'isExpired' => $isExpired,
                'limitDate' => $limitDate !== 'Sin fecha' ? Carbon::parse($limitDate) : null,
            ]);
        }
    }

    $studentDocs = $studentDocs->sortBy(function ($item) {
        return $item['limitDate'] ? $item['limitDate']->timestamp : PHP_INT_MAX;
    })->values();

    $totalAdmin   = $adminDocs->count();
    $totalStudent = $studentDocs->count();
    $total        = $totalAdmin + $totalStudent;
@endphp

@if($total === 0)
@else
<div class="space-y-5">

    {{-- ══════════════════════════════════════════════
         SECCIÓN 1 — Documentos informativos (admin_only)
    ════════════════════════════════════════════════ --}}
    @if($totalAdmin > 0)
        <div x-data="{ open: false }"
            class="rounded-2xl bg-[var(--color-card-bg)] overflow-hidden">

            <button @click="open = !open"
                    class="w-full flex items-center justify-between gap-3 px-5 py-4 hover:bg-[var(--color-card-bg-hover)] transition-colors focus:outline-none group">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-[var(--color-icon-bg)] flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-paperclip text-[var(--color-secondary)] text-xs"></i>
                    </div>
                    <div class="text-left">
                        <p class="text-sm font-bold text-[var(--color-primary-2)] tracking-wide leading-none">Documentos informativos</p>
                        <p class="text-xs text-[var(--color-secondary)] mt-1 leading-none">
                            {{ $totalAdmin }} {{ $totalAdmin === 1 ? 'archivo disponible' : 'archivos disponibles' }} · Solo lectura
                        </p>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <span class="hidden sm:inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-medium bg-[var(--color-icon-bg)] text-[var(--color-secondary)]">
                        <i class="fas fa-lock text-[9px]"></i>
                        Sin entrega requerida
                    </span>
                    <i class="fas fa-chevron-down text-xs text-[var(--color-secondary)] transition-transform duration-200 group-hover:text-[var(--color-text)]"
                    :class="{ 'rotate-180': open }"></i>
                </div>
            </button>

            <div x-show="open" x-collapse>
                <div class="border-t border-[var(--color-border-hover)] divide-y divide-[var(--color-border-hover)]/50">
                    @foreach($adminDocs as $item)
                        @php
                            $document  = $item['document'];
                            $adminFiles = $this->getFileToDisplay($document);
                        @endphp
                        <div wire:key="admin-doc-{{ $document->id }}"
                            class="flex items-center gap-3 px-5 py-3.5 hover:bg-[var(--color-icon-bg)]/40 transition-colors">

                            <div class="w-9 h-9 rounded-lg bg-[var(--color-icon-bg)] border border-[var(--color-border-hover)] flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-file-alt text-[var(--color-secondary)] text-sm"></i>
                            </div>

                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-semibold text-[var(--color-primary-2)] truncate">{{ $document->name }}</p>

                                @if($document->file && ($document->file->firman || $document->file->observations))
                                    <div x-data="{ open: false }">
                                        <button @click.stop="open = !open"
                                                class="inline-flex items-center gap-1 text-xs text-[var(--color-secondary)] hover:text-[var(--color-text)] transition-colors mt-1 focus:outline-none">
                                            <i class="fas fa-info-circle text-[10px]"></i>
                                            <span>Ver detalles</span>
                                            <i class="fas fa-chevron-down text-[9px] transition-transform duration-200" :class="{ 'rotate-180': open }"></i>
                                        </button>
                                        <div x-show="open" x-collapse class="mt-2 pl-2.5 border-l-2 border-[var(--color-border-hover)] space-y-1.5">
                                            @if($document->file->firman)
                                                <div>
                                                    <p class="text-xs font-semibold text-[var(--color-secondary)] mb-0.5">
                                                        <i class="fas fa-signature mr-1"></i>Firman:
                                                    </p>
                                                    <p class="text-xs text-[var(--color-secondary)] whitespace-pre-line">{{ $document->file->firman }}</p>
                                                </div>
                                            @endif
                                            @if($document->file->observations)
                                                <div>
                                                    <p class="text-xs font-semibold text-[var(--color-secondary)] mb-0.5">
                                                        <i class="fas fa-info-circle mr-1"></i>Observaciones:
                                                    </p>
                                                    <p class="text-xs text-[var(--color-secondary)] whitespace-pre-line">{{ $document->file->observations }}</p>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endif
                            </div>

                            @if($adminFiles)
                                <div class="flex items-center gap-1.5 flex-shrink-0">
                                    @foreach($adminFiles as $file)
                                        @php $isPdf = str_ends_with(strtolower($file['path']), '.pdf'); @endphp
                                        <div class="relative group/btn">
                                            <button wire:click="previewFile('{{ $file['path'] }}','{{ $file['name'] }}')"
                                                    class="w-8 h-8 flex items-center justify-center rounded-md transition
                                                        bg-[var(--color-icon-bg)] text-[var(--color-icon)]
                                                        hover:bg-[var(--color-icon-bg-hover)] hover:text-[var(--color-icon-hover)]">
                                                <i class="fas {{ $isPdf ? 'fa-file-pdf' : ($file['type']==='individual' ? 'fa-file' : 'fa-file-word') }} text-sm"></i>
                                            </button>
                                            <div class="absolute bottom-full mb-1.5 left-1/2 -translate-x-1/2 px-2 py-1 text-xs rounded
                                                        opacity-0 group-hover/btn:opacity-100 transition-opacity whitespace-nowrap pointer-events-none z-20
                                                        bg-[var(--color-icon-bg-hover)] text-[var(--color-icon-hover)]">
                                                {{ $isPdf ? 'Ver PDF' : ($file['type']==='individual' ? 'Ver archivo' : 'Ver Word') }}
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <span class="text-xs text-[var(--color-secondary)] italic flex-shrink-0">Sin archivo</span>
                            @endif

                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    {{-- ══════════════════════════════════════════════
         SECCIÓN 2 — Timeline interactivo del estudiante
    ════════════════════════════════════════════════ --}}
    @if($totalStudent > 0)

        @if($totalAdmin > 0)
            <div class="flex items-center gap-2.5 px-1 mt-10">
                <div>
                    <p class="text-sm font-bold text-[var(--color-primary-2)] tracking-wide leading-none">Tus entregas</p>
                    <p class="text-xs text-[var(--color-secondary)] mt-1 leading-none">
                        {{ $totalStudent }} {{ $totalStudent === 1 ? 'documento' : 'documentos' }} · Ordenados por fecha límite
                    </p>
                </div>
            </div>
        @endif

        <div class="relative">

            <div class="absolute left-[23px] top-0 bottom-0 w-px bg-[var(--color-border-hover)] z-0"
                style="top: 12px; bottom: 12px;"></div>

            <div class="space-y-3">
                @foreach($studentDocs as $index => $item)
                    @php
                        $document  = $item['document'];
                        $isExpired = $item['isExpired'];
                        $limitDate = $item['limitDate'];
                        $step      = $index + 1;

                        $canUpload  = $this->canUploadFile($document);
                        $hasFile    = (bool) $document->student_file_name;
                        $adminFiles = $this->getFileToDisplay($document);

                        $statusConfig = [
                            'revisado'    => ['label' => 'Aprobado',    'bg' => 'bg-green-500/10',  'text' => 'text-green-400',  'icon' => 'fa-check-circle'],
                            'rechazado'   => ['label' => 'Rechazado',   'bg' => 'bg-red-500/10',    'text' => 'text-red-400',    'icon' => 'fa-times-circle'],
                            'en_revision' => ['label' => 'En revisión', 'bg' => 'bg-blue-500/10',   'text' => 'text-blue-400',   'icon' => 'fa-clock'],
                        ];

                        $isApproved = $document->status === 'revisado';
                        $isDue      = $limitDate && $limitDate->isToday();
                        $isSoon     = $limitDate && $limitDate->isTomorrow();

                        $dateFormatted = $limitDate
                            ? $limitDate->locale('es')->isoFormat('DD MMM YYYY')
                            : null;

                        $daysLeft = null;
                        if ($limitDate && !$isExpired && !$isDue && !$isSoon) {
                            $daysLeft = now()->diffInDays($limitDate);
                        }

                        $status = $statusConfig[$document->status] ?? null;
                    @endphp

                    <div wire:key="timeline-item-{{ $document->id }}" class="relative flex gap-4 items-start group">

                        {{-- Nodo del timeline --}}
                        <div class="relative z-10 flex-shrink-0">

                            @if($isApproved)
                                {{-- ✅ Aprobado → verde --}}
                                <div class="w-[46px] h-[46px] rounded-full bg-[var(--color-card-bg)] flex items-center justify-center">
                                    <i class="fas fa-check text-green-400 text-sm"></i>
                                </div>

                            @elseif($document->status === 'rechazado')
                                {{-- ❌ Rechazado → rojo --}}
                                <div class="w-[46px] h-[46px] rounded-full bg-[var(--color-card-bg)] flex items-center justify-center">
                                    <span class="text-sm font-bold text-red-400">{{ $step }}</span>
                                </div>

                            @elseif($hasFile && $document->status === 'en_revision')
                                {{-- 🔵 Subido, en revisión → azul --}}
                                <div class="w-[46px] h-[46px] rounded-full bg-[var(--color-card-bg)] flex items-center justify-center">
                                    <span class="text-sm font-bold text-blue-400">{{ $step }}</span>
                                </div>

                            @elseif($isExpired)
                                {{-- ⛔ Vencido sin entregar → rojo --}}
                                <div class="w-[46px] h-[46px] rounded-full bg-[var(--color-card-bg)] flex items-center justify-center">
                                    <span class="text-sm font-bold text-[var(--color-secondary)]">{{ $step }}</span>
                                </div>

                            @elseif($isDue || $isSoon)
                                {{-- ⚠️ Vence hoy o mañana → color primario con botón subir --}}
                                <div class="w-[46px] h-[46px] rounded-full bg-[var(--color-card-bg)] flex items-center justify-center">
                                    @if($canUpload)
                                        <button type="button"
                                                onclick="document.getElementById('fileInput-{{ $document->id }}').click()"
                                                class="w-full h-full flex items-center justify-center rounded-full focus:outline-none">
                                            <i class="fas fa-upload text-[var(--color-primary)] text-sm"></i>
                                        </button>
                                    @else
                                        <span class="text-sm font-bold text-[var(--color-primary)]">{{ $step }}</span>
                                    @endif
                                </div>

                            @else
                                {{-- ⚪ Normal, sin entregar → secondary con botón subir --}}
                                <div class="w-[46px] h-[46px] rounded-full bg-[var(--color-card-bg)] border border-[var(--color-border-hover)] flex items-center justify-center">
                                    @if($canUpload)
                                        <button type="button"
                                                onclick="document.getElementById('fileInput-{{ $document->id }}').click()"
                                                class="w-full h-full flex items-center justify-center rounded-full focus:outline-none opacity-60 hover:opacity-100 transition-opacity">
                                            <i class="fas fa-upload text-[var(--color-secondary)] text-sm"></i>
                                        </button>
                                    @else
                                        <span class="text-sm font-bold text-[var(--color-secondary)]">{{ $step }}</span>
                                    @endif
                                </div>

                            @endif
                        </div>

                        {{-- Card del documento — fondo uniforme en todos los casos --}}
                        <div class="flex-1 min-w-0 mb-1 rounded-xl bg-[var(--color-card-bg)]
                            @if($isDue && !$isApproved) shadow-[0_0_20px_rgba(var(--color-primary-rgb),0.08)] @endif
                            @if($isExpired) opacity-75 @endif
                            transition-all duration-200 px-4 py-3.5">

                            <div class="flex items-start justify-between gap-3">
                                <div class="flex-1 min-w-0">

                                    {{-- Nombre + badge --}}
                                    <div class="flex items-center gap-2 flex-wrap">
                                        <h4 class="font-semibold text-[var(--color-primary-2)] text-sm">{{ $document->name }}</h4>

                                        @if($hasFile && $status)
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-xs font-medium {{ $status['bg'] }} {{ $status['text'] }}">
                                                <i class="fas {{ $status['icon'] }} text-[10px]"></i>
                                                {{ $status['label'] }}
                                            </span>
                                        @elseif($isExpired && !$hasFile)
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-xs font-medium bg-[var(--color-icon-bg)] text-[var(--color-secondary)]">
                                                <i class="fas fa-exclamation-circle text-[10px]"></i>
                                                Vencido
                                            </span>
                                        @endif
                                    </div>

                                    {{-- Archivo entregado / mensaje sin entregar --}}
                                    @if($hasFile)
                                        @php
                                            $nameParts = explode('_'.$student->control_number, $document->student_file_name);
                                            $baseName  = $nameParts[0];
                                            $ext       = pathinfo($document->student_file_name, PATHINFO_EXTENSION);
                                        @endphp
                                        <div class="flex items-center gap-1.5 mt-1">
                                            <i class="fas fa-paperclip text-xs text-[var(--color-secondary)]"></i>
                                            <span class="text-xs text-[var(--color-secondary)] truncate">{{ $baseName }}.{{ $ext }}</span>
                                        </div>
                                    @elseif($canUpload && !$isExpired)
                                        <p class="text-xs text-[var(--color-secondary)] italic mt-0.5">Sin entregar
                                            @if($document->file)
                                                · Máx. {{ $this->formatSize($document->file->max_size * 1024) }}
                                            @endif
                                        </p>
                                    @else
                                        <p class="text-xs text-[var(--color-secondary)] italic mt-0.5">Sin entregar</p>
                                    @endif

                                    {{-- Info adicional colapsable — mismo estilo en ambas secciones --}}
                                    @if($document->file && ($document->file->firman || $document->file->observations))
                                        <div x-data="{ open: false }" class="mt-1.5">
                                            <button @click.stop="open = !open"
                                                    class="inline-flex items-center gap-1 text-xs text-[var(--color-secondary)] hover:text-[var(--color-text)] transition-colors focus:outline-none">
                                                <i class="fas fa-info-circle text-[10px]"></i>
                                                <span>Ver detalles</span>
                                                <i class="fas fa-chevron-down text-[9px] transition-transform duration-200" :class="{ 'rotate-180': open }"></i>
                                            </button>
                                            <div x-show="open" x-collapse class="mt-1.5 pl-2.5 border-l-2 border-[var(--color-border-hover)] space-y-1.5">
                                                @if($document->file->firman)
                                                    <div>
                                                        <p class="text-xs font-semibold text-[var(--color-secondary)] mb-0.5">
                                                            <i class="fas fa-signature mr-1"></i>Firman:
                                                        </p>
                                                        <p class="text-xs text-[var(--color-secondary)] whitespace-pre-line">{{ $document->file->firman }}</p>
                                                    </div>
                                                @endif
                                                @if($document->file->observations)
                                                    <div>
                                                        <p class="text-xs font-semibold text-[var(--color-secondary)] mb-0.5">
                                                            <i class="fas fa-info-circle mr-1"></i>Observaciones:
                                                        </p>
                                                        <p class="text-xs text-[var(--color-secondary)] whitespace-pre-line">{{ $document->file->observations }}</p>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    @endif
                                </div>

                                {{-- Columna derecha: fecha + acciones --}}
                                <div class="flex-shrink-0 flex flex-col items-end gap-2">

                                    {{-- Fecha límite — color uniforme en todos los casos --}}
                                    @if($dateFormatted)
                                        <div class="text-right">
                                            <p class="text-[10px] font-semibold text-[var(--color-secondary)] leading-none mb-0.5">Fecha límite</p>
                                            <p class="text-sm font-bold text-[var(--color-primary-2)]">
                                                {{ $dateFormatted }}
                                            </p>
                                        </div>
                                    @endif

                                    <div class="flex items-center gap-1">

                                        {{-- Archivos del admin --}}
                                        @if($adminFiles)
                                            @foreach($adminFiles as $file)
                                                @php $isPdf = str_ends_with(strtolower($file['path']), '.pdf'); @endphp
                                                <div class="relative group/btn">
                                                    <button wire:click="previewFile('{{ $file['path'] }}','{{ $file['name'] }}')"
                                                            class="w-8 h-8 flex items-center justify-center rounded-md transition
                                                                bg-[var(--color-icon-bg)] text-[var(--color-icon)]
                                                                hover:bg-[var(--color-icon-bg-hover)] hover:text-[var(--color-icon-hover)]">
                                                        <i class="fas {{ $isPdf ? 'fa-file-pdf' : ($file['type']==='individual' ? 'fa-file' : 'fa-file-word') }} text-sm"></i>
                                                    </button>
                                                    <div class="absolute bottom-full mb-1.5 left-1/2 -translate-x-1/2 px-2 py-1 text-xs rounded
                                                                opacity-0 group-hover/btn:opacity-100 transition-opacity whitespace-nowrap pointer-events-none z-20
                                                                bg-[var(--color-icon-bg-hover)] text-[var(--color-icon-hover)]">
                                                        {{ $isPdf ? 'Ver PDF' : ($file['type']==='individual' ? 'Ver archivo' : 'Ver Word') }}
                                                    </div>
                                                </div>
                                            @endforeach
                                        @endif

                                        {{-- Ver mi archivo --}}
                                        @if($canUpload && $document->student_file_path)
                                            <div class="relative group/btn">
                                                <button wire:click="previewFile('{{ $document->student_file_path }}','{{ $document->student_file_name }}')"
                                                        class="w-8 h-8 flex items-center justify-center rounded-md transition
                                                            bg-[var(--color-icon-bg)] text-[var(--color-icon)]
                                                            hover:bg-[var(--color-icon-bg-hover)] hover:text-[var(--color-icon-hover)]">
                                                    <i class="fas fa-eye text-sm"></i>
                                                </button>
                                                <div class="absolute bottom-full mb-1.5 left-1/2 -translate-x-1/2 px-2 py-1 text-xs rounded
                                                            opacity-0 group-hover/btn:opacity-100 transition-opacity whitespace-nowrap pointer-events-none z-20
                                                            bg-[var(--color-icon-bg-hover)] text-[var(--color-icon-hover)]">
                                                    Ver mi archivo
                                                </div>
                                            </div>
                                        @endif

                                        

                                        {{-- Subir / Reemplazar --}}
                                        @if($canUpload && !$isExpired)
                                            <div class="relative group/btn">
                                                <button type="button"
                                                        onclick="document.getElementById('fileInput-{{ $document->id }}').click()"
                                                        class="w-8 h-8 flex items-center justify-center rounded-md transition cursor-pointer
                                                            bg-[var(--color-icon-bg)] text-[var(--color-icon)]
                                                            hover:bg-[var(--color-icon-bg-hover)] hover:text-[var(--color-icon-hover)]">
                                                    <i class="fas {{ $hasFile ? 'fa-sync-alt' : 'fa-upload' }} text-sm"></i>
                                                </button>
                                                <div class="absolute bottom-full mb-1.5 left-1/2 -translate-x-1/2 px-2 py-1 text-xs rounded
                                                            opacity-0 group-hover/btn:opacity-100 transition-opacity whitespace-nowrap pointer-events-none z-20
                                                            bg-[var(--color-icon-bg-hover)] text-[var(--color-icon-hover)]">
                                                    {{ $hasFile ? 'Reemplazar' : 'Subir archivo' }}
                                                </div>
                                            </div>
                                            <input type="file" id="fileInput-{{ $document->id }}" class="hidden"
                                                wire:model="fileUpload.{{ $document->id }}" accept=".pdf">
                                        @endif

                                        {{-- Comentarios --}}
                                        @if(!empty(trim($document->comments ?? '')))
                                            <div class="relative group/btn">
                                                <button wire:click="openComments({{ $document->id }})"
                                                        class="w-8 h-8 flex items-center justify-center rounded-md transition
                                                            bg-[var(--color-icon-bg)] text-[var(--color-icon)]
                                                            hover:bg-[var(--color-icon-bg-hover)] hover:text-[var(--color-icon-hover)]">
                                                    <i class="fas fa-comment-dots text-sm"></i>
                                                </button>
                                                <div class="absolute bottom-full mb-1.5 left-1/2 -translate-x-1/2 px-2 py-1 text-xs rounded
                                                            opacity-0 group-hover/btn:opacity-100 transition-opacity whitespace-nowrap pointer-events-none z-20
                                                            bg-[var(--color-icon-bg-hover)] text-[var(--color-icon-hover)]">
                                                    Comentarios
                                                </div>
                                            </div>
                                        @endif

                                    </div>
                                </div>
                            </div>

                            {{-- Zona de subida expandida (doc urgente de hoy sin archivo) --}}
                            @if($canUpload && !$hasFile && !$isExpired && $isDue)
                                <div class="mt-3 border border-dashed border-[var(--color-border-hover)] rounded-lg p-4 text-center
                                            hover:border-[var(--color-primary)]/50 transition-colors cursor-pointer"
                                    onclick="document.getElementById('fileInput-{{ $document->id }}').click()">
                                    <i class="fas fa-cloud-upload-alt text-2xl text-[var(--color-secondary)] mb-1.5 block"></i>
                                    <p class="text-sm font-medium text-[var(--color-secondary)]">Arrastra tu archivo aquí</p>
                                    <p class="text-xs text-[var(--color-secondary)] opacity-60 mt-0.5">
                                        Soporta PDF
                                        @if($document->file && $document->file->max_size)
                                            · Máx. {{ $this->formatSize($document->file->max_size * 1024) }}
                                        @endif
                                    </p>
                                </div>
                            @endif  

                        </div>
                    </div>
                @endforeach
            </div>
        </div>

    @endif
</div>
@endif