<div class="min-h-screen">
    <div class="max-w-[1600px] mx-auto p-4 lg:p-6">
        
        <!-- Mensaje si no tiene perfil de estudiante -->
        @if(!$student)
            <div class="flex items-center justify-center min-h-[80vh] px-4">
                <div class="max-w-md w-full">
                    <div class="bg-[var(--index-card-bg)] rounded-2xl shadow-2xl p-8 text-center backdrop-blur-sm">
                        <div class="relative w-20 h-20 bg-[var(--index-icon-bg)] rounded-full flex items-center justify-center mx-auto mb-6 
                                    before:content-[''] before:absolute before:inset-0 before:rounded-full before:bg-[var(--index-icon-bg)] 
                                    before:animate-ping before:opacity-20">
                            <i class="fas fa-user-slash text-4xl text-[var(--index-icon-text)] relative z-10"></i>
                        </div>
                        <h2 class="text-2xl font-bold text-[var(--index-text-primary)] mb-3">
                            Sin Perfil de Estudiante
                        </h2>
                        <p class="text-[var(--index-text-secondary)] mb-8 leading-relaxed max-w-sm mx-auto">
                            Para acceder a los documentos académicos, necesitas tener un perfil de estudiante activo en el sistema.
                        </p>
                        <a href="{{ route('students.profile') }}"
                        class="group relative inline-flex items-center justify-center px-4 py-2 rounded-[var(--radius-md)]
                                bg-[var(--index-btn-primary-bg)]! text-[var(--index-btn-primary-text)]!
                                shadow-lg shadow-[var(--index-btn-primary-shadow)]
                                hover:bg-[var(--index-btn-primary-hover)]! hover:shadow-xl hover:-translate-y-0.5
                                transition-all duration-300 disabled:opacity-60 disabled:cursor-not-allowed gap-2">
                            <span>Completar perfil</span>
                            <i class="fas fa-arrow-right text-sm group-hover:translate-x-1 transition-transform"></i>
                        </a>
                    </div>
                </div>
            </div>

        @elseif($student->status === 'pendiente')
            <div class="flex items-center justify-center min-h-[80vh] px-4">
                <div class="max-w-md w-full">
                    <div class="bg-[var(--index-card-bg)] rounded-2xl shadow-2xl p-8 text-center backdrop-blur-sm">
                        <div class="relative w-20 h-20 
                                    bg-[var(--index-icon-bg)]
                                    rounded-full flex items-center justify-center mx-auto mb-6
                                    before:content-[''] before:absolute before:inset-0 before:rounded-full
                                    before:bg-[var(--index-icon-bg)]
                                    before:animate-ping before:opacity-20">
                            <i class="fas fa-clock text-4xl text-[var(--index-icon-text)] relative z-10"></i>
                        </div>
                        <h2 class="text-2xl font-bold text-[var(--index-text-primary)] mb-3">
                            Perfil en revisión
                        </h2>
                        <p class="text-[var(--index-text-secondary)] mb-8 leading-relaxed max-w-sm mx-auto">
                            Tu perfil fue enviado correctamente y está siendo revisado por un administrador.
                            Te notificaremos cuando el proceso haya finalizado.
                        </p>
                    </div>
                </div>
            </div>

        @elseif($student->status === 'rechazado')
            <div class="flex items-center justify-center min-h-[80vh] px-4">
                <div class="max-w-md w-full">
                    <div class="bg-[var(--index-card-bg)] rounded-2xl shadow-2xl p-8 text-center backdrop-blur-sm">
                        <div class="relative w-20 h-20
                                    bg-[var(--index-status-rejected-bg)]
                                    rounded-full flex items-center justify-center mx-auto mb-6
                                    before:content-[''] before:absolute before:inset-0 before:rounded-full
                                    before:bg-[var(--index-status-rejected-bg)]
                                    before:animate-ping before:opacity-20">
                            <i class="fas fa-times-circle text-4xl text-[var(--index-status-rejected-icon)] relative z-10"></i>
                        </div>
                        <h2 class="text-2xl font-bold text-[var(--index-text-primary)] mb-3">
                            Perfil rechazado
                        </h2>
                        <p class="text-[var(--index-text-secondary)] mb-6 leading-relaxed max-w-sm mx-auto">
                            Por favor, actualiza la información necesaria y vuelve a enviarlo para su revisión.
                        </p>
                        <a href="{{ route('students.profile') }}"
                        class="inline-flex items-center gap-2
                                rounded-[var(--radius-md)]
                                bg-[var(--settings-btn-primary)]!
                                text-[var(--settings-btn-primary-text)]!
                                shadow-lg shadow-[var(--settings-btn-primary-shadow)]
                                hover:bg-[var(--settings-btn-primary-hover)]!
                                hover:shadow-xl hover:-translate-y-0.5
                                active:translate-y-0
                                transition-all duration-300
                                px-6 py-3
                                font-medium">
                            <span>Corregir perfil</span>
                            <i class="fas fa-edit text-sm group-hover:translate-x-1 transition-transform"></i>
                        </a>
                    </div>
                </div>
            </div>
        @else
        
       <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
            <!-- COLUMNA PRINCIPAL - Documentos -->
            <div class="lg:col-span-8 space-y-6">
                
                <!-- Header compacto -->
                <div class="bg-[var(--index-card-bg)] rounded-xl shadow-sm p-6">
                    <div class="flex items-center justify-between">
                        <x-auth-header
                            title="Mis Documentos"
                            description="Gestiona tus entregas académicas"
                            :center="false"
                        />
                        
                        @php
                            $allDocs = [];
                            foreach($documents as $docs) {
                                $allDocs = array_merge($allDocs, $docs->toArray());
                            }
                            $totalDocs = count($allDocs);
                            $rejectedCount = collect($allDocs)->where('status', 'rechazado')->count();
                            $approvedCount = collect($allDocs)->where('status', 'revisado')->count();
                            $reviewCount = collect($allDocs)
                                ->filter(fn($doc) => $doc['status'] === 'en_revision' && !empty($doc['student_file_path']))
                                ->count();
                            $progress = $totalDocs > 0 ? round(($approvedCount / $totalDocs) * 100) : 0;
                        @endphp
                        
                        <!-- Mini progress -->
                        <div class="flex items-center gap-4">
                            <div class="text-right">
                                <div class="text-2xl font-bold text-[var(--index-text-primary)]">{{ $approvedCount }}<span class="text-[var(--index-text-secondary)]">/{{ $totalDocs }}</span></div>
                                <div class="text-xs text-[var(--index-text-secondary)]">Completados</div>
                            </div>
                            <div class="relative w-16 h-16">
                                <svg class="transform -rotate-90 w-16 h-16">
                                    <circle cx="32" cy="32" r="28" stroke="currentColor" stroke-width="4" fill="transparent" class="text-[var(--index-border)]"/>
                                    <circle cx="32" cy="32" r="28" stroke="currentColor" stroke-width="4" fill="transparent" 
                                            stroke-dasharray="{{ 2 * 3.14159 * 28 }}" 
                                            stroke-dashoffset="{{ 2 * 3.14159 * 28 * (1 - $progress / 100) }}"
                                            class="text-[var(--index-accent)] transition-all duration-1000"/>
                                </svg>
                                <div class="absolute inset-0 flex items-center justify-center">
                                    <span class="text-sm font-bold text-[var(--index-text-primary)]">{{ $progress }}%</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div x-data="{ 
                    openCard: null,
                    toggleCard(date) {
                        this.openCard = this.openCard === date ? null : date;
                        }
                    }" class="bg-[var(--index-card-bg)] rounded-xl shadow-sm p-6">
                    <div class="space-y-4">
                        
                        {{-- Documentos Informativos (admin_only) --}}
                        @if($adminOnlyDocuments->count() > 0)
                            <div class="bg-[var(--index-card-bg)] rounded-xl shadow-sm overflow-hidden transition-all duration-300 hover:shadow-md mb-6">
                                
                                <button 
                                    @click="toggleCard('admin-only-docs')"
                                    class="w-full bg-gradient-to-r from-[var(--index-content-bg)] to-[var(--index-content-bg)] px-6 py-4 hover:brightness-105 transition-all focus:outline-none">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center gap-3">
                                            <div class="text-left">
                                                <p class="text-white/70 text-xs font-medium">Documentos</p>
                                                <h3 class="text-white text-lg font-bold">Información y Recursos</h3>
                                            </div>
                                        </div>
                                        <div class="flex items-center gap-3">
                                            <div class="flex items-center gap-2 px-3 py-1 bg-white/20 backdrop-blur rounded-full">
                                                <i class="fas fa-file-alt text-white text-xs"></i>
                                                <span class="text-white text-xs font-bold">{{ $adminOnlyDocuments->count() }}</span>
                                            </div>
                                            <div class="w-8 h-8 bg-white/20 backdrop-blur rounded-lg flex items-center justify-center transition-transform duration-300"
                                                :class="{ 'rotate-180': openCard === 'admin-only-docs' }">
                                                <i class="fas fa-chevron-down text-white text-sm"></i>
                                            </div>
                                        </div>
                                    </div>
                                </button>

                                <div x-show="openCard === 'admin-only-docs'"
                                    x-collapse
                                    x-cloak>
                                    <div class="p-4 space-y-3">
                                        @foreach($adminOnlyDocuments as $document)
                                            @php
                                                $showAdminFiles = $this->shouldShowAdminFiles($document);
                                                $adminFiles = $this->getFileToDisplay($document);
                                            @endphp

                                            <div class="bg-[var(--index-content-bg)] rounded-lg border border-[var(--index-border)] hover:border-[var(--index-accent)] transition-all p-4">
                                                <div class="flex items-center gap-4">
                                                    <div class="flex-1 min-w-0">
                                                        <h4 class="font-semibold text-[var(--index-text-primary)] text-sm truncate">{{ $document->name }}</h4>
                                                        <p class="text-xs text-[var(--index-text-secondary)] italic">Documento</p>
                                                    </div>

                                                    <div class="flex-shrink-0 flex items-center gap-2">
                                                        @if($showAdminFiles && $adminFiles)
                                                            <div class="flex gap-1">
                                                                @foreach($adminFiles as $file)
                                                                    @php
                                                                        $isPdf = str_ends_with(strtolower($file['path']), '.pdf');
                                                                        $isWord = str_ends_with(strtolower($file['path']), '.docx') || str_ends_with(strtolower($file['path']), '.doc');
                                                                    @endphp
                                                                    <div class="relative group">
                                                                        <button wire:click="previewFile('{{ $file['path'] }}','{{ $file['name'] }}')"
                                                                                class="w-8 h-8 flex items-center justify-center rounded-lg transition text-xs
                                                                                {{ $isPdf
                                                                                    ? 'bg-[var(--period-detail-btn-pdf-bg)] text-[var(--period-detail-btn-pdf-text)] hover:bg-[var(--period-detail-btn-pdf-hover)]'
                                                                                    : 'bg-[var(--period-detail-btn-word-bg)] text-[var(--period-detail-btn-word-text)] hover:bg-[var(--period-detail-btn-word-hover)]' }}">
                                                                            <i class="fas {{ $isPdf ? 'fa-file-pdf' : ($file['type']==='individual' ? 'fa-file' : 'fa-file-word') }}"></i>
                                                                        </button>
                                                                        <div class="absolute bottom-full mb-2 left-1/2 -translate-x-1/2 px-2 py-1 text-xs
                                                                                    rounded opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap
                                                                                    {{ $isPdf ? 'bg-[var(--index-content-bg)] text-white' : 'bg-[var(--index-content-bg)] text-white' }}">
                                                                            {{ $isPdf ? 'Ver PDF' : ($file['type']==='individual' ? 'Ver Word' : 'Ver Word') }}
                                                                        </div>
                                                                    </div>
                                                                @endforeach
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>

                                                @if(($document->file && ($document->file->firman || $document->file->observations)) || !empty(trim($document->comments)))
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

                                                            @if(!empty(trim($document->comments)))
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
                                                                @if($document->file && $document->file->firman)
                                                                    <div class="bg-[var(--period-detail-expanded-bg)] rounded p-3">
                                                                        <p class="text-xs font-semibold text-[var(--index-text-primary)] mb-1">
                                                                            <i class="fas fa-signature mr-1"></i>Firman:
                                                                        </p>
                                                                        <p class="text-xs text-[var(--index-text-secondary)]">{{ $document->file->firman }}</p>
                                                                    </div>
                                                                @endif
                                                                @if($document->file && $document->file->observations)
                                                                    <div class="bg-[var(--period-detail-expanded-bg)] rounded p-3">
                                                                        <p class="text-xs font-semibold text-[var(--index-text-primary)] mb-1">
                                                                            <i class="fas fa-info-circle mr-1"></i>Observaciones:
                                                                        </p>
                                                                        <p class="text-xs text-[var(--index-text-secondary)]">{{ $document->file->observations }}</p>
                                                                    </div>
                                                                @endif
                                                            </div>
                                                        @endif
                                                    </div>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endif

                        {{-- Documentos con fecha límite (user_only y bidirectional) --}}
                        @forelse ($documents as $limitDate => $docs)
                            @php 
                                $isExpired = $limitDate !== 'Sin fecha' && now()->gt(Carbon\Carbon::parse($limitDate)->endOfDay());

                                $totalDocs = count($docs);
                                $uploadedDocs = collect($docs)
                                    ->filter(fn($d) => $d->student_file_name)
                                    ->count();

                                $allApproved = collect($docs)
                                    ->every(fn($doc) => $doc->status === 'revisado');

                                $allUploaded = $uploadedDocs === $totalDocs;
                                $noneUploaded = $uploadedDocs === 0;

                                $cardId = 'card-' . str_replace(' ', '-', $limitDate);
                            @endphp

                            <div class="bg-[var(--index-card-bg)] rounded-xl shadow-sm overflow-hidden transition-all duration-300 hover:shadow-md">
                                
                                <!-- Header clickeable -->
                                <button 
                                    @click="toggleCard('{{ $cardId }}')"
                                    class="w-full bg-gradient-to-r
                                        {{ $isExpired
                                            ? 'from-[var(--period-detail-status-rejected-bg)] to-[var(--index-card-header-bg)]'
                                            : ($allApproved
                                                ? 'from-[var(--period-detail-status-approved-bg)] to-[var(--index-card-header-approved-bg)]'
                                                : 'from-[var(--index-content-bg)] to-[var(--index-content-bg)]') }}
                                        px-6 py-4 hover:brightness-105 transition-all focus:outline-none">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center gap-3">
                                            <div class="text-left">
                                                <p class="{{ $isExpired || $allApproved ? 'text-[var(--index-text-secondary)]' : 'text-white/70' }} text-xs font-medium">Fecha límite</p>
                                                <h3 class="{{ $isExpired || $allApproved ? 'text-[var(--index-text-primary)]' : 'text-white' }} text-lg font-bold">
                                                {{ 
                                                    $limitDate !== 'Sin fecha'
                                                        ? \Carbon\Carbon::parse($limitDate)->locale('es')->isoFormat('DD MMM YYYY')
                                                        : 'Sin fecha'
                                                }}
                                                </h3>
                                            </div>
                                        </div>
                                        
                                        <div class="flex items-center gap-3">
                                            @php
                                                $statusText = '';
                                                $statusIcon = '';

                                                if ($allApproved) {
                                                    $statusText = 'COMPLETADO';
                                                } elseif ($isExpired && $allUploaded) {
                                                    $statusText = 'ENTREGADO';
                                                } elseif ($isExpired && !$noneUploaded) {
                                                    $statusText = 'INCOMPLETO';
                                                } elseif ($isExpired && $noneUploaded) {
                                                    $statusText = 'NO ENTREGADO';
                                                } else {
                                                    if($limitDate !== 'Sin fecha') {
                                                        $today = \Carbon\Carbon::today();
                                                        $limit = \Carbon\Carbon::parse($limitDate)->startOfDay();

                                                        if ($limit->eq($today)) {
                                                            $statusText = 'HOY';
                                                        } elseif ($limit->eq($today->copy()->addDay())) {
                                                            $statusText = 'MAÑANA';
                                                        } elseif ($limit->gt($today->copy()->addDay())) {
                                                            $daysLeft = $today->diffInDays($limit);
                                                            $statusText = "$daysLeft DÍAS";
                                                        }
                                                    }
                                                }
                                            @endphp

                                            @if($statusText)
                                                <span class="px-3 py-1 backdrop-blur rounded-full text-xs font-bold flex items-center gap-1.5
                                                    {{ $isExpired || $allApproved ? 'bg-[var(--period-detail-card-indicator-bg)] text-[var(--index-text-primary)]' : 'bg-white/20 text-white' }}">
                                                    {!! $statusIcon !!}{{ $statusText }}
                                                </span>
                                            @endif

                                            <!-- Contador de documentos -->
                                            <div class="flex items-center gap-2 px-3 py-1 backdrop-blur rounded-full
                                                {{ $isExpired || $allApproved ? 'bg-[var(--period-detail-card-indicator-bg)]' : 'bg-white/20' }}">
                                                <i class="fas fa-file-alt text-xs {{ $isExpired || $allApproved ? 'text-[var(--index-text-primary)]' : 'text-white' }}"></i>
                                                <span class="text-xs font-bold {{ $isExpired || $allApproved ? 'text-[var(--index-text-primary)]' : 'text-white' }}">{{ count($docs) }}</span>
                                            </div>

                                            <!-- Icono de acordeón -->
                                            <div class="w-8 h-8 backdrop-blur rounded-lg flex items-center justify-center transition-transform duration-300
                                                {{ $isExpired || $allApproved ? 'bg-[var(--period-detail-card-indicator-bg)]' : 'bg-white/20' }}"
                                                :class="{ 'rotate-180': openCard === '{{ $cardId }}' }">
                                                <i class="fas fa-chevron-down text-sm {{ $isExpired || $allApproved ? 'text-[var(--index-text-primary)]' : 'text-white' }}"></i>
                                            </div>
                                        </div>
                                    </div>
                                </button>

                                <!-- Contenido expandible -->
                                <div x-show="openCard === '{{ $cardId }}'"
                                    x-collapse
                                    x-cloak>
                                    <div class="p-4 space-y-3">
                                        @foreach($docs as $document)
                                            @php
                                                $uploadMode = $document->file?->upload_mode ?? 'bidirectional';
                                                $isIndividual = $document->file?->is_individual ?? false;
                                                $canUpload = $this->canUploadFile($document);
                                                $showAdminFiles = $this->shouldShowAdminFiles($document);
                                                $adminFiles = $this->getFileToDisplay($document);
                                            @endphp

                                            <div class="bg-[var(--index-content-bg)] rounded-lg border border-[var(--index-border)] hover:border-[var(--index-accent)] transition-all p-4">
                                                <div class="flex items-center gap-4">
                                                    
                                                    <!-- Info -->
                                                    <div class="flex-1 min-w-0">
                                                        <h4 class="font-semibold text-[var(--index-text-primary)] text-sm truncate">{{ $document->name }}</h4>
                                                        
                                                        @if($document->student_file_name)
                                                            @php
                                                                $nameParts = explode('_'.$student->control_number, $document->student_file_name);
                                                                $baseName = $nameParts[0];
                                                                $extension = pathinfo($document->student_file_name, PATHINFO_EXTENSION);
                                                            @endphp
                                                            <p class="text-xs text-[var(--index-text-secondary)] truncate">{{ $baseName }}.{{ $extension }}</p>
                                                        @else
                                                            <p class="text-xs text-[var(--index-text-secondary)] italic">Sin entregar</p>
                                                        @endif
                                                    </div>

                                                    <!-- Status badge -->
                                                    @if($document->student_file_name && $canUpload)
                                                        @php
                                                            $statusNames = [
                                                                'revisado' => 'Revisado',
                                                                'rechazado' => 'Rechazado',
                                                                'en_revision' => 'En revisión',
                                                            ];
                                                        @endphp

                                                        {{-- ⚠️ NO CAMBIAR: estos status usan sus propias variables semánticas --}}
                                                        <span class="flex-shrink-0 px-2.5 py-1 rounded-full text-xs font-bold
                                                            {{ $document->status === 'revisado'    ? 'bg-[var(--index-status-approved-bg)] text-[var(--index-status-approved-icon)]'  : '' }}
                                                            {{ $document->status === 'rechazado'   ? 'bg-[var(--index-status-rejected-bg)] text-[var(--index-status-rejected-icon)]'   : '' }}
                                                            {{ $document->status === 'en_revision' ? 'bg-[var(--index-status-normal-bg)]  text-[var(--index-status-normal-icon)]'      : '' }}">
                                                            {{ $statusNames[$document->status] ?? ucfirst($document->status) }}
                                                        </span>
                                                    @endif

                                                    <!-- Acciones rápidas -->
                                                    <div class="flex-shrink-0 flex items-center gap-2">

                                                        <!-- Archivos del Administrador -->
                                                        @if($showAdminFiles && $adminFiles)
                                                            <div class="flex gap-1">
                                                                @foreach($adminFiles as $file)
                                                                    @php
                                                                        $isPdf = str_ends_with(strtolower($file['path']), '.pdf');
                                                                        $isWord = str_ends_with(strtolower($file['path']), '.docx') || str_ends_with(strtolower($file['path']), '.doc');
                                                                    @endphp
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
                                                                        <div class="absolute bottom-full mb-2 left-1/2 -translate-x-1/2 px-2 py-1 text-xs
                                                                                    rounded opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap
                                                                                    {{ $isExpired
                                                                                        ? 'bg-[var(--index-text-secondary)] text-white'
                                                                                        : ($isPdf ? 'bg-[var(--index-brand-primary)] text-white' : 'bg-[var(--index-brand-secondary)] text-white') }}">
                                                                            {{ $isPdf ? 'Ver PDF' : ($file['type']==='individual' ? 'Ver archivo asignado' : 'Ver Word') }}
                                                                        </div>
                                                                    </div>
                                                                @endforeach
                                                            </div>
                                                        @endif

                                                        <!-- Botón Ver mi archivo -->
                                                        @if($canUpload && $document->student_file_path)
                                                            <div class="relative group">
                                                                <button wire:click="previewFile('{{ $document->student_file_path }}','{{ $document->student_file_name }}')"
                                                                        class="w-8 h-8 flex items-center justify-center rounded-lg bg-[var(--period-detail-btn-bg-g)] text-[var(--period-detail-btn-text-g)] hover:bg-[var(--period-detail-btn-hover-g)] transition text-xs cursor-pointer">
                                                                    <i class="fas fa-eye"></i>
                                                                </button>
                                                                <div class="absolute bottom-full mb-2 left-1/2 -translate-x-1/2 px-2 py-1 text-xs
                                                                            rounded opacity-0 group-hover:opacity-100 transition-opacity
                                                                            whitespace-nowrap bg-[var(--index-brand-primary)] text-white">
                                                                    Ver mi archivo
                                                                </div>
                                                            </div>
                                                        @endif

                                                        <!-- Botón Subir/Reemplazar -->
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
                                                                <div class="absolute bottom-full mb-2 left-1/2 -translate-x-1/2 px-2 py-1 text-xs
                                                                            rounded opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap
                                                                            {{ $isExpired
                                                                                ? 'bg-[var(--index-text-secondary)] text-white'
                                                                                : ($document->student_file_name ? 'bg-[var(--index-brand-primary)] text-white' : 'bg-[var(--index-btn-primary-bg)] text-white') }}">
                                                                    {{ $document->student_file_name ? 'Reemplazar' : 'Subir archivo' }}
                                                                </div>
                                                            </div>

                                                            <input type="file" id="fileInput-{{ $document->id }}" class="hidden" 
                                                                wire:model="fileUpload.{{ $document->id }}" accept=".pdf">
                                                        @endif
                                                    </div>
                                                </div>

                                                <!-- Tamaño máximo -->
                                                @if($canUpload && $document->file)
                                                    <div class="mt-3">
                                                        <div class="flex items-center gap-1.5 text-xs text-[var(--index-text-secondary)]">
                                                            <i class="fas fa-info-circle text-[var(--index-accent)]"></i>
                                                            <span>Tamaño máximo:</span>
                                                            <span class="font-bold text-[var(--index-accent)]">
                                                                {{ $this->formatSize($document->file->max_size * 1024) }}
                                                            </span>
                                                        </div>
                                                    </div>
                                                @endif

                                                <!-- Información adicional y comentarios -->
                                                @if(($document->file && ($document->file->firman || $document->file->observations)) || !empty(trim($document->comments)))
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

                                                            @if(!empty(trim($document->comments)))
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
                                                                        <p class="text-xs font-semibold text-[var(--index-text-primary)] mb-1">
                                                                            <i class="fas fa-signature mr-1"></i>Firman:
                                                                        </p>
                                                                        <p class="text-xs text-[var(--index-text-secondary)] whitespace-pre-line">{{ $document->file->firman }}</p>
                                                                    </div>
                                                                @endif
                                                                @if($document->file->observations)
                                                                    <div class="bg-[var(--period-detail-expanded-bg)] rounded p-3">
                                                                        <p class="text-xs font-semibold text-[var(--index-text-primary)] mb-1">
                                                                            <i class="fas fa-info-circle mr-1"></i>Observaciones:
                                                                        </p>
                                                                        <p class="text-xs text-[var(--index-text-secondary)] whitespace-pre-line">{{ $document->file->observations }}</p>
                                                                    </div>
                                                                @endif
                                                            </div>
                                                        @endif
                                                    </div>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @empty
                            @if($adminOnlyDocuments->count() === 0)
                                <div class="rounded-xl shadow-sm p-12 text-center">
                                    <div class="w-20 h-20 bg-[var(--index-content-bg)] rounded-2xl flex items-center justify-center mx-auto mb-4">
                                        <i class="fas fa-folder-open text-4xl text-[var(--index-text-primary)]"></i>
                                    </div>
                                    <h3 class="text-xl font-bold text-[var(--index-text-primary)] mb-2">No hay documentos</h3>
                                    <p class="text-[var(--index-text-secondary)] text-sm">Cuando se te asignen documentos aparecerán aquí</p>
                                </div>
                            @endif
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- SIDEBAR - Calendario y Alertas -->
            <div class="lg:col-span-4">
                @php
                    $upcomingDocs = [];
                    $expiredDocs = [];
                    $calendarEvents = [];

                    foreach($documents as $limitDate => $docs) {
                        if($limitDate !== 'Sin fecha') {
                            $date = Carbon\Carbon::parse($limitDate);
                            $daysLeft = now()->diffInDays($date, false);
                            
                            foreach($docs as $doc) {
                                $calendarEvents[] = [
                                    'date' => $date->format('Y-m-d'),
                                    'doc' => $doc,
                                    'status' => $doc->status,
                                    'isExpired' => $daysLeft < 0,
                                    'hasFile' => !empty($doc->student_file_path)
                                ];
                                
                                if($doc->status !== 'revisado') {
                                    if($daysLeft < 0) {
                                        $expiredDocs[] = ['doc' => $doc, 'date' => $date, 'days' => abs($daysLeft)];
                                    } elseif($daysLeft <= 7) {
                                        $upcomingDocs[] = ['doc' => $doc, 'date' => $date, 'days' => $daysLeft];
                                    }
                                }
                            }
                        }
                    }
                @endphp

                <div class="lg:sticky lg:top-6 bg-[var(--index-card-bg)] rounded-2xl shadow-xl overflow-hidden">
                    
                    <!-- Header del panel -->
                    <div class="bg-gradient-to-r from-[var(--index-content-bg)] to-[var(--index-content-bg)] p-6">
                        <div class="flex items-center gap-3">
                            <div>
                                <h3 class="text-white font-bold text-lg">Panel de Control</h3>
                                <p class="text-white/70 text-xs">Seguimiento de entregas</p>
                            </div>
                        </div>
                    </div>

                    <div class="sidebar-scroll overflow-y-auto" style="max-height: calc(100vh - 200px);">
                        <div class="p-6 space-y-6">
                            
                            <!-- CALENDARIO INTERACTIVO -->
                            <div class="bg-[var(--index-card-bg)] rounded-xl shadow-lg border border-[var(--index-border)] relative"
                                x-data="{
                                    currentDate: new Date(),
                                    selectedDate: null,
                                    selectedEvents: [],
                                    calendarEvents: {{ json_encode($calendarEvents) }},

                                    init() {
                                        Livewire.on('calendar-updated', (data) => {
                                            this.calendarEvents = data.calendarEvents;
                                        });
                                    },
                                    
                                    get currentMonth() {
                                        return this.currentDate.toLocaleString('es-MX', { month: 'long' });
                                    },
                                    
                                    get currentYear() {
                                        return this.currentDate.getFullYear();
                                    },
                                    
                                    get daysInMonth() {
                                        const year = this.currentDate.getFullYear();
                                        const month = this.currentDate.getMonth();
                                        const firstDay = new Date(year, month, 1).getDay();
                                        const daysInMonth = new Date(year, month + 1, 0).getDate();
                                        const days = [];
                                        
                                        for (let i = 0; i < firstDay; i++) {
                                            days.push(null);
                                        }
                                        
                                        for (let day = 1; day <= daysInMonth; day++) {
                                            const dateStr = `${year}-${String(month + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
                                            const events = this.getEventsForDate(dateStr);
                                            const isToday = this.isToday(year, month, day);
                                            
                                            days.push({ day, dateStr, events, isToday });
                                        }
                                        
                                        return days;
                                    },
                                    
                                    getEventsForDate(dateStr) {
                                        return this.calendarEvents.filter(e => e.date === dateStr);
                                    },
                                    
                                    isToday(year, month, day) {
                                        const today = new Date();
                                        return today.getFullYear() === year && 
                                                today.getMonth() === month && 
                                                today.getDate() === day;
                                    },
                                    
                                    prevMonth() {
                                        this.currentDate = new Date(this.currentDate.getFullYear(), this.currentDate.getMonth() - 1);
                                    },
                                    
                                    nextMonth() {
                                        this.currentDate = new Date(this.currentDate.getFullYear(), this.currentDate.getMonth() + 1);
                                    },
                                    
                                    selectDay(day) {
                                        if (day && day.events.length > 0) {
                                            this.selectedDate = day.dateStr;
                                            this.selectedEvents = day.events;
                                        }
                                    },
                                    
                                    getDayColor(events) {
                                        if (!events.length) return '';
                                        if (events.some(e => e.status === 'rechazado')) return 'bg-red-500';
                                        if (events.some(e => e.isExpired && !e.hasFile && e.status !== 'revisado')) return 'bg-orange-400';
                                        if (events.some(e => e.status === 'en_revision' && e.hasFile)) return 'bg-blue-500';
                                        if (events.some(e => e.status === 'en_revision' && !e.hasFile)) return 'bg-yellow-400';
                                        if (events.some(e => e.status === 'revisado')) return 'bg-green-500';
                                        return 'bg-yellow-400';
                                    }
                                }">

                                <!-- Header del calendario -->
                                <div class="bg-gradient-to-r from-[var(--index-content-bg)] to-[var(--index-content-bg)] p-4 rounded-t-xl">
                                    <div class="flex items-center justify-between mb-3">
                                        <button @click="prevMonth()"
                                                class="w-8 h-8 flex items-center justify-center rounded-lg text-white transition-transform duration-200 hover:scale-125">
                                            <i class="fas fa-chevron-left text-xs"></i>
                                        </button>

                                        <div class="text-center">
                                            <h3 class="text-base font-bold text-white capitalize" x-text="currentMonth"></h3>
                                            <p class="text-xs text-white/70" x-text="currentYear"></p>
                                        </div>

                                        <button @click="nextMonth()"
                                                class="w-8 h-8 flex items-center justify-center rounded-lg text-white transition-transform duration-200 hover:scale-125">
                                            <i class="fas fa-chevron-right text-xs"></i>
                                        </button>
                                    </div>
                                    
                                    <!-- Leyenda -->
                                    <div class="flex flex-wrap justify-center gap-x-3 gap-y-1 text-xs">
                                        <div class="flex items-center gap-1">
                                            <div class="w-2 h-2 rounded-full bg-green-500"></div>
                                            <span class="text-white/80">Aprobado</span>
                                        </div>
                                        <div class="flex items-center gap-1">
                                            <div class="w-2 h-2 rounded-full bg-blue-500"></div>
                                            <span class="text-white/80">En revisión</span>
                                        </div>
                                        <div class="flex items-center gap-1">
                                            <div class="w-2 h-2 rounded-full bg-red-500"></div>
                                            <span class="text-white/80">Rechazado</span>
                                        </div>
                                        <div class="flex items-center gap-1">
                                            <div class="w-2 h-2 rounded-full bg-yellow-400"></div>
                                            <span class="text-white/80">Pendiente</span>
                                        </div>
                                        <div class="flex items-center gap-1">
                                            <div class="w-2 h-2 rounded-full bg-orange-400"></div>
                                            <span class="text-white/80">Vencido</span>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Grid del calendario -->
                                <div class="p-4">
                                    <div class="grid grid-cols-7 gap-1 mb-2">
                                        <div class="text-center text-xs font-bold text-[var(--index-text-secondary)] py-2">Dom</div>
                                        <div class="text-center text-xs font-bold text-[var(--index-text-secondary)] py-2">Lun</div>
                                        <div class="text-center text-xs font-bold text-[var(--index-text-secondary)] py-2">Mar</div>
                                        <div class="text-center text-xs font-bold text-[var(--index-text-secondary)] py-2">Mié</div>
                                        <div class="text-center text-xs font-bold text-[var(--index-text-secondary)] py-2">Jue</div>
                                        <div class="text-center text-xs font-bold text-[var(--index-text-secondary)] py-2">Vie</div>
                                        <div class="text-center text-xs font-bold text-[var(--index-text-secondary)] py-2">Sáb</div>
                                    </div>
                                    
                                    <div class="grid grid-cols-7 gap-1">
                                        <template x-for="(day, index) in daysInMonth" :key="index">
                                            <div>
                                                <button 
                                                    x-show="day !== null"
                                                    @click="selectDay(day)"
                                                    :class="{
                                                        'bg-[var(--index-icon-bg)] border-2 border-[var(--index-accent)]': day?.isToday,
                                                        'hover:bg-[var(--index-content-bg)]': day?.events.length === 0,
                                                        'cursor-pointer hover:scale-105': day?.events.length > 0,
                                                        'opacity-50': day?.events.length === 0
                                                    }"
                                                    class="w-full aspect-square flex flex-col items-center justify-center rounded-md transition-all relative group">
                                                    <span class="text-sm font-medium text-[var(--index-text-primary)]" x-text="day?.day"></span>
                                                    
                                                    <div x-show="day?.events.length > 0" class="flex gap-0.5 mt-1">
                                                        <template x-for="event in day?.events.slice(0, 3)">
                                                            <div :class="getDayColor([event]) + ' w-1 h-1 rounded-full'"></div>
                                                        </template>
                                                    </div>                                                    
                                                    <div x-show="day?.events.length > 0" 
                                                        class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 px-2 py-1 bg-gray-900 dark:bg-gray-700 text-white text-xs rounded opacity-0 group-hover:opacity-100 transition pointer-events-none whitespace-nowrap z-10">
                                                        <span x-text="day?.events.length + ' documento(s)'"></span>
                                                    </div>
                                                </button>
                                            </div>
                                        </template>
                                    </div>
                                </div>
                                
                                <!-- Eventos del día seleccionado -->
                                <div x-show="selectedEvents.length > 0" 
                                     x-transition
                                     class="border-t border-[var(--index-border)] p-4 bg-[var(--index-content-bg)] rounded-b-xl">
                                    <div class="flex items-center justify-between mb-3">
                                        <h4 class="font-bold text-[var(--index-text-primary)] text-sm">
                                            Documentos del día 
                                            <span x-text="
                                                (() => {
                                                    if (!selectedDate) return '';
                                                    const parts = selectedDate.split('-');
                                                    const year = parseInt(parts[0], 10);
                                                    const month = parseInt(parts[1], 10) - 1;
                                                    const day = parseInt(parts[2], 10);
                                                    return new Date(year, month, day).toLocaleDateString('es-MX', { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' });
                                                })()
                                            "></span>
                                        </h4>
                                        <button @click="selectedDate = null; selectedEvents = []" 
                                                class="w-8 h-8 flex items-center justify-center rounded-full text-[var(--index-text-secondary)] hover:text-[var(--index-text-primary)] transition">
                                            <i class="fas fa-times text-base"></i>
                                        </button>
                                    </div>

                                    <div class="space-y-2 max-h-48 overflow-y-auto sidebar-scroll">
                                        <template x-for="event in selectedEvents" :key="event.doc.id">
                                            <div class="p-3 rounded-lg transition-all hover:shadow-md bg-[var(--index-card-bg)] border border-[var(--index-border)]">
                                                <p class="text-xs font-semibold text-[var(--index-text-primary)]" x-text="event.doc.name"></p>
                                                <div class="flex items-center justify-between mt-1">
                                                    <span class="text-[var(--index-text-secondary)] text-[10px] font-bold"
                                                        x-text="(
                                                            () => {
                                                                if (event.status === 'revisado') return 'APROBADO';
                                                                if (event.status === 'rechazado') return 'RECHAZADO';
                                                                if (event.isExpired && !event.hasFile) return 'VENCIDO';
                                                                if (event.status === 'en_revision' && event.hasFile) return 'EN REVISIÓN';
                                                                if (event.status === 'en_revision' && !event.hasFile) return 'PENDIENTE';
                                                                return 'PENDIENTE';
                                                            }
                                                        )()">
                                                    </span>
                                                </div>
                                            </div>
                                        </template>
                                    </div>
                                </div>
                            </div>

                            <!-- Resumen de alertas -->
                            <div class="bg-[var(--index-card-bg)] rounded-xl shadow-lg overflow-hidden border border-[var(--index-border)] p-5">
                                <div class="flex items-center gap-3 mb-4">
                                    <div>
                                        <h3 class="font-bold text-[var(--index-text-primary)]">Resumen</h3>
                                        <p class="text-xs text-[var(--index-text-secondary)]">Estado de tus entregas</p>
                                    </div>
                                </div>

                                <div class="space-y-3">
                                    <!-- Por vencer -->
                                    <div class="flex items-center justify-between p-3 rounded-lg bg-[var(--index-content-bg)]">
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 bg-[var(--index-status-pending)] rounded-lg flex items-center justify-center">
                                                <i class="fas fa-clock text-white text-sm"></i>
                                            </div>
                                            <div>
                                                <p class="text-sm font-bold text-[var(--index-text-primary)]">Por vencer</p>
                                                <p class="text-xs text-[var(--index-text-secondary)]">Próximos 7 días</p>
                                            </div>
                                        </div>
                                        {{-- ⚠️ NO CAMBIAR --}}
                                        <span class="text-2xl font-bold text-[var(--index-status-pending)]">
                                            {{ count($upcomingDocs) }}
                                        </span>
                                    </div>
                                    
                                    <!-- Rechazados -->
                                    <div class="flex items-center justify-between p-3 rounded-lg bg-[var(--index-content-bg)]">
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 bg-[var(--index-status-rejected-icon)] rounded-lg flex items-center justify-center">
                                                <i class="fas fa-times text-white text-sm"></i>
                                            </div>
                                            <div>
                                                <p class="text-sm font-bold text-[var(--index-text-primary)]">Rechazados</p>
                                                <p class="text-xs text-[var(--index-text-secondary)]">Requiere corrección</p>
                                            </div>
                                        </div>
                                        {{-- ⚠️ NO CAMBIAR --}}
                                        <span class="text-2xl font-bold text-[var(--index-status-rejected-icon)]">
                                            {{ $rejectedCount }}
                                        </span>
                                    </div>

                                    <!-- En Revisión -->
                                    <div class="flex items-center justify-between p-3 rounded-lg bg-[var(--index-content-bg)]">
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 bg-[var(--index-status-normal-icon)] rounded-lg flex items-center justify-center">
                                                <i class="fas fa-hourglass-half text-white text-sm"></i>
                                            </div>
                                            <div>
                                                <p class="text-sm font-bold text-[var(--index-text-primary)]">En Revisión</p>
                                                <p class="text-xs text-[var(--index-text-secondary)]">Pendiente de revisión</p>
                                            </div>
                                        </div>
                                        {{-- ⚠️ NO CAMBIAR --}}
                                        <span class="text-2xl font-bold text-[var(--index-status-normal-icon)]">
                                            {{ $reviewCount }}
                                        </span>
                                    </div>

                                    <!-- Aprobados -->
                                    <div class="flex items-center justify-between p-3 rounded-lg bg-[var(--index-content-bg)]">
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 bg-[var(--index-status-approved-icon)] rounded-lg flex items-center justify-center">
                                                <i class="fas fa-check text-white text-sm"></i>
                                            </div>
                                            <div>
                                                <p class="text-sm font-bold text-[var(--index-text-primary)]">Aprobados</p>
                                                <p class="text-xs text-[var(--index-text-secondary)]">Finalizados</p>
                                            </div>
                                        </div>
                                        {{-- ⚠️ NO CAMBIAR --}}
                                        <span class="text-2xl font-bold text-[var(--index-status-approved-icon)]">
                                            {{ $approvedCount }}
                                        </span>
                                    </div>

                                    <!-- Vencidos -->
                                    <div class="flex items-center justify-between p-3 rounded-lg bg-[var(--index-content-bg)]"
                                        x-data="{ expiredDocs: {{ json_encode($calendarEvents) }} }">
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 bg-orange-500 rounded-lg flex items-center justify-center">
                                                <i class="fas fa-exclamation text-white text-sm"></i>
                                            </div>
                                            <div>
                                                <p class="text-sm font-bold text-[var(--index-text-primary)]">Vencidos</p>
                                                <p class="text-xs text-[var(--index-text-secondary)]">Requiere acción</p>
                                            </div>
                                        </div>
                                        {{-- ⚠️ NO CAMBIAR: contador Alpine --}}
                                        <span class="text-2xl font-bold text-orange-500"
                                            x-text="expiredDocs.filter(doc => doc.isExpired && !doc.hasFile && doc.status !== 'revisado').length">
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif 
    </div>

    <!-- Modales -->
    @if($previewPath)
        <flux:modal
            :dismissible="false"
            wire:model="previewPath"
            class="w-[95vw] sm:w-[90vw] md:w-[85vw] lg:w-[1100px] h-[90vh] sm:h-[85vh] max-w-[1400px]"
        >
            @php
                $cleanName = $previewName;
                $pos = strpos($cleanName, '_');
                if ($pos !== false) {
                    $cleanName = substr($cleanName, 0, $pos);
                }
                $ext = strtolower(pathinfo($previewPath, PATHINFO_EXTENSION));
            @endphp

            <div class="flex flex-col h-full">

                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 pb-3 sm:pb-4 border-b dark:border-gray-700 shrink-0">
                    <div class="min-w-0 flex-1">
                        <flux:heading size="lg" class="text-[var(--modal-text-primary)] truncate">
                            {{ $cleanName }}
                        </flux:heading>
                        <p class="text-xs sm:text-sm text-[var(--modal-text-secondary)] mt-1">
                            Vista previa del documento
                        </p>
                    </div>
                </div>

                <div class="flex-1 overflow-hidden py-3 sm:py-4 min-h-0">
                    @if($ext === 'pdf')
                        <iframe
                            src="{{ asset('storage/'.$previewPath) }}"
                            class="w-full h-full rounded-lg sm:rounded-xl border dark:border-gray-700"
                        ></iframe>
                    @else
                        <div class="h-full flex flex-col items-center justify-center text-center px-4">
                            <div class="inline-flex items-center justify-center w-16 h-16 sm:w-20 sm:h-20 bg-[var(--index-icon-bg)] rounded-xl sm:rounded-2xl mb-3 sm:mb-4">
                                <i class="fas fa-file-download text-3xl sm:text-4xl text-[var(--index-icon-text)]"></i>
                            </div>
                            <p class="text-[var(--modal-text-primary)] text-base sm:text-lg">
                                Este archivo no se puede previsualizar
                            </p>
                        </div>
                    @endif
                </div>

                <div class="flex flex-col-reverse sm:flex-row justify-end gap-2 sm:gap-3 pt-3 sm:pt-4 border-t dark:border-gray-700 shrink-0">
                    <a href="{{ asset('storage/'.$previewPath) }}" download 
                    class="
                        group relative
                        inline-flex items-center justify-center
                        px-4 py-2
                        rounded-[var(--radius-md)]
                        bg-[var(--modal-btn-document-descargar)]!
                        text-[var(--modal-btn-document-descargar-text)]!
                        shadow-lg shadow-[var(--modal-btn-document-descargar-shadow)]
                        hover:bg-[var(--modal-btn-document-descargar-hover)]!
                        hover:shadow-xl hover:-translate-y-0.5
                        transition-all duration-300
                        disabled:opacity-60 disabled:cursor-not-allowed
                        gap-2
                    ">
                        <i class="fas fa-download"></i>
                        Descargar
                    </a>

                    <flux:button 
                        variant="primary" 
                        wire:click="$set('previewPath', null)" 
                        class="
                            group relative
                            inline-flex items-center justify-center
                            px-4 py-2
                            rounded-[var(--radius-md)]
                            bg-[var(--modal-btn-document-cerrar)]!
                            text-[var(--modal-btn-document-cerrar-text)]!
                            shadow-lg shadow-[var(--modal-btn-document-cerrar-shadow)]
                            hover:bg-[var(--modal-btn-document-cerrar-hover)]!
                            hover:shadow-xl hover:-translate-y-0.5
                            transition-all duration-300
                            disabled:opacity-60 disabled:cursor-not-allowed
                            gap-2
                        ">
                        Cerrar
                    </flux:button>
                </div>
            </div>
        </flux:modal>
    @endif

    @if($isCommentsModalOpen && $selectedDocument)
        <flux:modal
            :dismissible="false"
            wire:model="isCommentsModalOpen"
            class="w-[95vw] sm:w-[85vw] md:w-[600px] lg:w-[650px] max-w-[700px]"
        >
            <div class="flex flex-col h-[75vh] sm:h-[70vh] max-h-[600px]">

                <div class="flex items-start gap-3 pb-4 shrink-0">
                    <div class="min-w-0 flex-1">
                        <flux:heading size="lg" class="text-[var(--modal-text-primary)] mb-1">
                            Observaciones del Revisor
                        </flux:heading>
                        <p class="text-xs sm:text-sm text-[var(--modal-text-secondary)]">
                            Comentarios y sugerencias sobre tu documento
                        </p>
                    </div>
                </div>

                <div class="flex-1 overflow-y-auto overflow-x-hidden py-4 sidebar-scroll min-h-0">
                    @if($selectedDocument->comments)
                        <div class="relative">
                            <div class="pl-2 pr-2">
                                <div class="bg-[var(--index-content-bg)] rounded-2xl p-5 sm:p-6 shadow-sm border border-[var(--index-border)]">
                                    <div class="flex items-center gap-2 mb-3 pb-3 border-b border-[var(--index-border)]">
                                        <i class="fas fa-user-circle text-lg text-[var(--index-accent)]"></i>
                                        <span class="text-sm font-medium text-[var(--modal-text-primary)]">
                                            Revisor
                                        </span>
                                    </div>
                                    <div class="whitespace-pre-line break-words overflow-wrap-anywhere text-[var(--modal-text-secondary)] leading-relaxed text-sm sm:text-base">
                                        {{ $selectedDocument->comments }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="h-full flex flex-col items-center justify-center text-center px-4 py-12">
                            <div class="relative mb-6">
                                <div class="inline-flex items-center justify-center w-20 h-20 bg-[var(--index-content-bg)] rounded-2xl shadow-lg border border-[var(--index-border)]">
                                    <i class="fas fa-inbox text-4xl text-[var(--index-text-secondary)]"></i>
                                </div>
                                <div class="absolute -bottom-1 -right-1 w-8 h-8 bg-[var(--index-accent)] rounded-full flex items-center justify-center shadow-lg">
                                    <i class="fas fa-check text-white text-sm"></i>
                                </div>
                            </div>
                            <p class="text-base font-medium text-[var(--modal-text-primary)] mb-2">
                                Sin observaciones
                            </p>
                            <p class="text-sm text-[var(--modal-text-secondary)] max-w-xs">
                                No hay comentarios del revisor para este documento
                            </p>
                        </div>
                    @endif
                </div>

                <div class="flex justify-end gap-3 pt-4 border-t dark:border-gray-700 shrink-0">
                    <flux:button
                        wire:click="$set('isCommentsModalOpen', false)"
                        class="
                            group relative
                            inline-flex items-center justify-center
                            px-4 py-2
                            rounded-[var(--radius-md)]
                            bg-[var(--modal-btn-document-cerrar)]!
                            text-[var(--modal-btn-document-cerrar-text)]!
                            shadow-lg shadow-[var(--modal-btn-document-cerrar-shadow)]
                            hover:bg-[var(--modal-btn-document-cerrar-hover)]!
                            hover:shadow-xl hover:-translate-y-0.5
                            transition-all duration-300
                            disabled:opacity-60 disabled:cursor-not-allowed
                            gap-2
                        ">
                        Cerrar
                    </flux:button>
                </div>

            </div>
        </flux:modal>
    @endif

</div>