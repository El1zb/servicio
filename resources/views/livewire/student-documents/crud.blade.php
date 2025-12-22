<div class="min-h-screen">
    <div class="max-w-[1600px] mx-auto p-4 lg:p-6">
        
        <!-- Mensaje si no tiene perfil de estudiante -->
        @if(!$student)
            <div class="flex items-center justify-center min-h-[80vh]">
                <div class="max-w-md w-full">
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl p-8 text-center border-t-4 border-red-500">
                        <div class="w-20 h-20 bg-red-100 dark:bg-red-900/30 rounded-full flex items-center justify-center mx-auto mb-6">
                            <i class="fas fa-user-slash text-4xl text-red-600 dark:text-red-400"></i>
                        </div>
                        
                        <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-3">
                            Sin Perfil de Estudiante
                        </h2>
                        
                        <p class="text-gray-600 dark:text-gray-400 mb-6 leading-relaxed">
                            No se encontró un perfil de estudiante asociado a tu cuenta. Por favor, contacta al administrador para que te asigne un perfil.
                        </p>
                        
                        <div class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg p-4 mb-6">
                            <div class="flex items-start gap-3">
                                <i class="fas fa-info-circle text-yellow-600 dark:text-yellow-400 mt-0.5"></i>
                                <div class="text-left">
                                    <p class="text-sm font-semibold text-yellow-800 dark:text-yellow-300 mb-1">
                                        ¿Qué significa esto?
                                    </p>
                                    <p class="text-xs text-yellow-700 dark:text-yellow-400">
                                        Para acceder a los documentos académicos, necesitas tener un perfil de estudiante activo en el sistema.
                                    </p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="flex flex-col sm:flex-row gap-3 justify-center">
                            <a href="{{ route('students.profile') }}"
                                class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-indigo-600 text-white hover:bg-indigo-700 transition">
                                    <i class="fas fa-user-graduate"></i>
                                    Completar perfil de estudiante
                                </a>
                        </div>
                    </div>
                </div>
            </div>

        @elseif($student->status === 'pendiente')
            {{-- ⏳ PERFIL PENDIENTE --}}
            <div class="flex items-center justify-center min-h-[80vh]">
                <div class="max-w-md w-full">
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl p-8 text-center border-t-4 border-yellow-500">
                        
                        <div class="w-20 h-20 bg-yellow-100 dark:bg-yellow-900/30 rounded-full flex items-center justify-center mx-auto mb-6">
                            <i class="fas fa-clock text-4xl text-yellow-600 dark:text-yellow-400"></i>
                        </div>

                        <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-3">
                            Perfil en revisión
                        </h2>

                        <p class="text-gray-600 dark:text-gray-400 mb-6">
                            Tu perfil fue enviado y está siendo revisado por un administrador.
                        </p>

                        <a href="{{ route('students.profile') }}"
                        class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-indigo-600 text-white hover:bg-indigo-700 transition">
                            <i class="fas fa-user-graduate"></i>
                            Ver mi perfil
                        </a>
                    </div>
                </div>
            </div>

        @elseif($student->status === 'rechazado')

            {{-- ❌ PERFIL RECHAZADO --}}
            <div class="flex items-center justify-center min-h-[80vh]">
                <div class="max-w-md w-full">
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl p-8 text-center border-t-4 border-red-500">
                        
                        <div class="w-20 h-20 bg-red-100 dark:bg-red-900/30 rounded-full flex items-center justify-center mx-auto mb-6">
                            <i class="fas fa-times-circle text-4xl text-red-600 dark:text-red-400"></i>
                        </div>

                        <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-3">
                            Perfil rechazado
                        </h2>

                        <p class="text-gray-600 dark:text-gray-400 mb-6">
                            Tu perfil fue revisado y requiere correcciones antes de ser aprobado.
                        </p>

                        <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-4 mb-6 text-left text-sm text-red-700 dark:text-red-300">
                            Revisa cuidadosamente tu información y vuelve a enviarla.
                        </div>

                        <a href="{{ route('students.profile') }}"
                        class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-red-600 text-white hover:bg-red-700 transition">
                            <i class="fas fa-edit"></i>
                            Corregir perfil
                        </a>
                    </div>
                </div>
            </div>
        
        @else
        
       <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
            
            <!-- COLUMNA PRINCIPAL - Documentos -->
            <div class="lg:col-span-8 space-y-6">
                
                <!-- Header compacto -->
                <div class="bg-[var(--student-document-bg)] rounded-xl shadow-sm p-6">
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

                            // En revisión: documentos con archivo subido y status en_revision
                            $reviewCount = collect($allDocs)
                                ->filter(fn($doc) => $doc['status'] === 'en_revision' && !empty($doc['student_file_path']))
                                ->count();
                            $progress = $totalDocs > 0 ? round(($approvedCount / $totalDocs) * 100) : 0;
                        @endphp
                        
                        <!-- Mini progress -->
                        <div class="flex items-center gap-4">
                            <div class="text-right">
                                <div class="text-2xl font-bold text-[var(--student-document-text-primary)]">{{ $approvedCount }}<span class="text-[var(--student-document-text-secondary)]">/{{ $totalDocs }}</span></div>
                                <div class="text-xs text-[var(--student-document-text-secondary)]">Completados</div>
                            </div>
                            <div class="relative w-16 h-16">
                                <svg class="transform -rotate-90 w-16 h-16">
                                    <circle cx="32" cy="32" r="28" stroke="currentColor" stroke-width="4" fill="transparent" class="text-[var(--student-document-progress)]"/>
                                    <circle cx="32" cy="32" r="28" stroke="currentColor" stroke-width="4" fill="transparent" 
                                            stroke-dasharray="{{ 2 * 3.14159 * 28 }}" 
                                            stroke-dashoffset="{{ 2 * 3.14159 * 28 * (1 - $progress / 100) }}"
                                            class="text-[var(--student-document-progress-indicador)] transition-all duration-1000"/>
                                </svg>
                                <div class="absolute inset-0 flex items-center justify-center">
                                    <span class="text-sm font-bold text-[var(--student-document-text-primary)]">{{ $progress }}%</span>
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
                    }" class="bg-[var(--student-document-bg)] rounded-xl shadow-sm p-6">
                        <div class="space-y-4">
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

                                <div class="bg-[var(--student-document-bg-card)] rounded-xl shadow-sm overflow-hidden transition-all duration-300 hover:shadow-md">
                                    
                                    <!-- Header clickeable -->
                                    <button 
                                        @click="toggleCard('{{ $cardId }}')"
                                        class="w-full bg-gradient-to-r {{ $isExpired ? 'from-[var(--student-document-bg-card-header-expired)] to-[var(--student-document-bg-card-header-expired-2)]' : ($allApproved ? 'from-[var(--student-document-bg-card-header-approved)] to-[var(--student-document-bg-card-header-approved-2)]' : 'from-[var(--student-document-bg-card-header)] to-[var(--student-document-bg-card-header-2)]') }} px-6 py-4 hover:brightness-105 transition-all focus:outline-none">
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center gap-3">
                                                <div class="text-left">
                                                    <p class="text-white/90 text-xs font-medium">Fecha límite</p>
                                                    <h3 class="text-white text-lg font-bold">
                                                        {{ $limitDate !== 'Sin fecha' ? Carbon\Carbon::parse($limitDate)->format('d M Y') : 'Sin fecha' }}
                                                    </h3>
                                                </div>
                                            </div>
                                            
                                            <div class="flex items-center gap-3">
                                                <!-- Badge de estado -->
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
                                                                $daysLeft = $today->diffInDays($limit); // siempre positivo
                                                                $statusText = "$daysLeft DÍAS";
                                                            }
                                                        }
                                                    }
                                                @endphp

                                                @if($statusText)
                                                    <span class="px-3 py-1 bg-[var(--student-document-bg-card-indicator)] backdrop-blur rounded-full text-[var(--student-document-text-primary)] text-xs font-bold flex items-center gap-1.5">
                                                        {!! $statusIcon !!}{{ $statusText }}
                                                    </span>
                                                @endif

                                                <!-- Contador de documentos -->
                                                <div class="flex items-center gap-2 px-3 py-1 bg-[var(--student-document-bg-card-indicator)] backdrop-blur rounded-full">
                                                    <i class="fas fa-file-alt text-[var(--student-document-text-primary)] text-xs"></i>
                                                    <span class="text-[var(--student-document-text-primary)] text-xs font-bold">{{ count($docs) }}</span>
                                                </div>

                                                <!-- Icono de acordeón -->
                                                <div class="w-8 h-8 bg-[var(--student-document-bg-card-indicator)] backdrop-blur rounded-lg flex items-center justify-center transition-transform duration-300"
                                                    :class="{ 'rotate-180': openCard === '{{ $cardId }}' }">
                                                    <i class="fas fa-chevron-down text-[var(--student-document-text-primary)] text-sm"></i>
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
                                                <div class="bg-[var(--student-document-bg-content)] rounded-lg border border-[var(--student-document-border-content)] hover:border-[var(--student-document-border-content-hover)] transition-all p-4">
                                                    <div class="flex items-center gap-4">
                                                        
                                                        <!-- Info -->
                                                        <div class="flex-1 min-w-0">
                                                            <h4 class="font-semibold text-[var(--student-document-text-primary)] text-sm truncate">{{ $document->name }}</h4>
                                                            @if($document->student_file_name)
                                                                @php
                                                                    $nameParts = explode('_'.$student->control_number, $document->student_file_name);
                                                                    $baseName = $nameParts[0];
                                                                    $extension = pathinfo($document->student_file_name, PATHINFO_EXTENSION);
                                                                @endphp
                                                                <p class="text-xs text-[var(--student-document-text-secondary)] truncate">{{ $baseName }}.{{ $extension }}</p>
                                                            @else
                                                                <p class="text-xs text-[var(--student-document-text-secondary)] italic">Sin entregar</p>
                                                            @endif
                                                        </div>

                                                        <!-- Status badge -->
                                                        @if($document->student_file_name)
                                                            @php
                                                                $statusNames = [
                                                                    'revisado' => 'Revisado',
                                                                    'rechazado' => 'Rechazado',
                                                                    'en_revision' => 'En revisión',
                                                                ];
                                                            @endphp

                                                            <span class="flex-shrink-0 px-2.5 py-1 rounded-full text-xs font-bold
                                                                {{ $document->status === 'revisado' ? 'bg-[var(--student-document-bg-content-status-approved)] text-[var(--student-document-text-content-status-approved-icon)]' : '' }}
                                                                {{ $document->status === 'rechazado' ? 'bg-[var(--student-document-bg-content-status-rejected)] text-[var(--student-document-text-content-status-rejected-icon)]' : '' }}
                                                                {{ $document->status === 'en_revision' ? 'bg-[var(--student-document-bg-content-status-normal)] text-[var(--student-document-text-content-status-normal-icon)]' : '' }}">
                                                                {{ $statusNames[$document->status] ?? ucfirst($document->status) }}
                                                            </span>
                                                        @endif

                                                        <!-- Acciones rápidas -->
                                                        <div class="flex-shrink-0 flex items-center gap-2">

                                                            <!-- Word y PDF -->
                                                            @if($document->file?->file_path || $document->file?->example_path)
                                                                <div class="flex gap-1">
                                                                    <!-- Botón Word -->
                                                                    @if($document->file?->file_path)
                                                                        <div class="relative group">
                                                                            <button wire:click="previewFile('{{ $document->file->file_path }}','{{ $document->file->name_file }}')"
                                                                                    class="w-8 h-8 flex items-center justify-center rounded-lg {{ $isExpired ? 'bg-[var(--student-document-bg-button-expired)] text-[var(--student-document-bg-button-icon-expired)] cursor-not-allowed' : 'bg-[var(--student-document-bg-button-word)] text-[var(--student-document-text-button)] hover:bg-[var(--student-document-bg-button-word-hover)] cursor-pointer' }} transition text-xs"
                                                                                    {{ $isExpired ? 'disabled' : '' }}>
                                                                                <i class="fas fa-file-word"></i>
                                                                            </button>
                                                                            <div class="absolute bottom-full mb-2 left-1/2 -translate-x-1/2 px-2 py-1 text-xs
                                                                                        rounded opacity-0 group-hover:opacity-100 transition-opacity
                                                                                        whitespace-nowrap {{ $isExpired ? 'bg-[var(--student-document-bg-tooltip-expired)] text-[var(--student-document-text-tooltip-expired)]' : 'bg-[var(--student-document-bg-tooltip-word)] text-[var(--student-document-text-button)]' }}">
                                                                                Ver Word
                                                                            </div>
                                                                        </div>
                                                                    @endif

                                                                    <!-- Botón PDF -->
                                                                    @if($document->file?->example_path)
                                                                        <div class="relative group">
                                                                            <button wire:click="previewFile('{{ $document->file->example_path }}','{{ $document->file->example_name_file }}')"
                                                                                    class="w-8 h-8 flex items-center justify-center rounded-lg {{ $isExpired ? 'bg-[var(--student-document-bg-button-expired)] text-[var(--student-document-bg-button-icon-expired)] cursor-not-allowed' : 'bg-[var(--student-document-bg-button-pdf)] text-[var(--student-document-text-button)] hover:bg-[var(--student-document-bg-button-pdf-hover)] cursor-pointer' }} transition text-xs"
                                                                                    {{ $isExpired ? 'disabled' : '' }}>
                                                                                <i class="fas fa-file-pdf"></i>
                                                                            </button>
                                                                            <div class="absolute bottom-full mb-2 left-1/2 -translate-x-1/2 px-2 py-1 text-xs
                                                                                        rounded opacity-0 group-hover:opacity-100 transition-opacity
                                                                                        whitespace-nowrap {{ $isExpired ? 'bg-[var(--student-document-bg-tooltip-expired)] text-[var(--student-document-text-tooltip-expired)]' : 'bg-[var(--student-document-bg-tooltip-pdf)] text-[var(--student-document-text-button)]' }}">
                                                                                Ver PDF
                                                                            </div>
                                                                        </div>
                                                                    @endif
                                                                </div>
                                                            @endif

                                                            <!-- Botón Ver mi archivo -->
                                                            @if($document->student_file_path)
                                                                <div class="relative group">
                                                                    <button wire:click="previewFile('{{ $document->student_file_path }}','{{ $document->student_file_name }}')"
                                                                            class="w-8 h-8 flex items-center justify-center rounded-lg bg-[var(--student-document-bg-button-view)] text-[var(--student-document-text-button-view)] hover:bg-[var(--student-document-bg-button-view-hover)] transition text-xs cursor-pointer">
                                                                        <i class="fas fa-eye"></i>
                                                                    </button>
                                                                    <div class="absolute bottom-full mb-2 left-1/2 -translate-x-1/2 px-2 py-1 text-xs
                                                                                rounded opacity-0 group-hover:opacity-100 transition-opacity
                                                                                whitespace-nowrap bg-[var(--student-document-bg-tooltip-view)] text-[var(--student-document-text-tooltip-view)]">
                                                                        Ver mi archivo
                                                                    </div>
                                                                </div>
                                                            @endif

                                                            <!-- Botón Subir/Reemplazar -->
                                                            <div class="relative group">
                                                                <button type="button" 
                                                                        onclick="document.getElementById('fileInput-{{ $document->id }}').click()" 
                                                                        class="w-8 h-8 flex items-center justify-center rounded-lg transition text-xs {{ $isExpired ? 'bg-[var(--student-document-bg-button-expired)] text-[var(--student-document-bg-button-icon-expired)] cursor-not-allowed' : ($document->student_file_name ? 'bg-[var(--student-document-bg-button-replace)] text-[var(--student-document-text-button)] hover:bg-[var(--student-document-bg-button-replace-hover)] cursor-pointer' : 'bg-[var(--student-document-bg-button-upload)] text-[var(--student-document-text-button)] hover:bg-[var(--student-document-bg-button-upload-hover)] cursor-pointer') }}"
                                                                        {{ $isExpired ? 'disabled' : '' }}>
                                                                    <i class="fas {{ $document->student_file_name ? 'fa-sync-alt' : 'fa-upload' }}"></i>
                                                                </button>
                                                                <div class="absolute bottom-full mb-2 left-1/2 -translate-x-1/2 px-2 py-1 text-xs
                                                                            rounded opacity-0 group-hover:opacity-100 transition-opacity
                                                                            whitespace-nowrap {{ $isExpired ? 'bg-[var(--student-document-bg-tooltip-expired)] text-[var(--student-document-text-tooltip-expired)]' : ($document->student_file_name ? 'bg-[var(--student-document-bg-tooltip-replace)] text-[var(--student-document-text-button)]' : 'bg-[var(--student-document-bg-tooltip-upload)] text-[var(--student-document-text-button)]') }}">
                                                                    {{ $document->student_file_name ? 'Reemplazar' : 'Subir archivo' }}
                                                                </div>
                                                            </div>

                                                            <input type="file" id="fileInput-{{ $document->id }}" class="hidden" 
                                                                wire:model="fileUpload.{{ $document->id }}" accept=".pdf">
                                                        </div>

                                                    </div>

                                                    <!-- Información de tamaño máximo -->
                                                    @if($document->file)
                                                        <div class="mt-3 pt-3">
                                                            <div class="flex items-center gap-2">
                                                                <div class="flex items-center gap-1.5 text-xs text-[var(--student-document-text-secondary)]">
                                                                    <i class="fas fa-info-circle text-[var(--student-document-size-color)]"></i>
                                                                    <span>Tamaño máximo:</span>
                                                                    <span class="font-bold text-[var(--student-document-size-color)]">
                                                                        {{ $this->formatSize($document->file->max_size * 1024) }}
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endif

                                                    <!-- Observaciones compactas -->
                                                    @if(!empty($document->comments) && $document->status === 'rechazado')
                                                        <div class="mt-3 pt-3 border-t border-[var(--student-document-border-separator)]">
                                                            <button wire:click="openComments({{ $document->id }})"
                                                                    class="flex items-center gap-2 text-[var(--student-document-text-comment)] hover:text-[var(--student-document-text-comment-hover)] text-xs font-medium">
                                                                <i class="fas fa-comment-dots"></i>
                                                                Ver observaciones del revisor
                                                                <i class="fas fa-chevron-right text-[10px]"></i>
                                                            </button>
                                                        </div>
                                                    @endif
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-12 text-center">
                                    <div class="w-20 h-20 bg-gray-100 dark:bg-gray-700 rounded-2xl flex items-center justify-center mx-auto mb-4">
                                        <i class="fas fa-folder-open text-4xl text-gray-400"></i>
                                    </div>
                                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">No hay documentos</h3>
                                    <p class="text-gray-500 dark:text-gray-400 text-sm">Cuando se te asignen documentos aparecerán aquí</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
            </div>

            <!-- SIDEBAR - Calendario y Alertas con Scroll Personalizado -->
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
                                    'hasFile' => !empty($doc->student_file_path) // <-- aquí agregamos
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

                <!-- Panel contenedor con scroll personalizado -->
                <div class="lg:sticky lg:top-6 bg-[var(--student-document-control-bg)] rounded-2xl shadow-xl overflow-hidden">
                    
                    <!-- Header fijo del panel -->
                    <div class="bg-gradient-to-r from-[var(--student-document-control-bg-header)] to-[var(--student-document-control-bg-header-2)] p-6">
                        <div class="flex items-center gap-3">
                            <div>
                                <h3 class="text-[var(--student-document-text-primary)] font-bold text-lg">Panel de Control</h3>
                                <p class="text-[var(--student-document-text-secondary)] text-xs">Seguimiento de entregas</p>
                            </div>
                        </div>
                    </div>

                    <!-- Contenedor con scroll personalizado -->
                    <div class="sidebar-scroll overflow-y-auto" style="max-height: calc(100vh - 200px);">
                        <div class="p-6 space-y-6">
                            
                            <!-- CALENDARIO INTERACTIVO -->
                            <div class="bg-[var(--student-document-calendar-bg)] rounded-xl shadow-lg overflow-hidden"
                                x-data="{
                                    currentDate: new Date(),
                                    selectedDate: null,
                                    selectedEvents: [],
                                    calendarEvents: {{ json_encode($calendarEvents) }},
                                    
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
                                        if (events.some(e => e.status === 'rechazado')) return 'bg-[var(--student-document-calendar-rejected)]';
                                        if (events.some(e => e.isExpired && !e.hasFile && e.status !== 'revisado')) return 'bg-[var(--student-document-calendar-expired)]'; // Solo marcar vencido si no hay archivo enviado                                        
                                        if (events.some(e => e.status === 'en_revision' && e.hasFile)) return 'bg-[var(--student-document-calendar-review)]'; // en revisión, archivo enviado
                                        if (events.some(e => e.status === 'en_revision' && !e.hasFile)) return 'bg-[var(--student-document-calendar-pending)]'; // pendiente, nada enviado
                                        if (events.some(e => e.status === 'revisado')) return 'bg-[var(--student-document-calendar-approved)]';
                                        return 'bg-[var(--student-document-calendar-pending)]'; // default pendiente
                                    }
                                }">
                                
                                <!-- Header del calendario -->
                                <div class="bg-gradient-to-r from-[var(--student-document-calendar-bg-header)] to-[var(--student-document-calendar-bg-header-2)] p-4">
                                    <div class="flex items-center justify-between mb-3">
                                        <button @click="prevMonth()"
                                                class="w-8 h-8 flex items-center justify-center rounded-lg text-[var(--student-document-text-primary)] transition-transform duration-200 hover:scale-125">
                                            <i class="fas fa-chevron-left text-xs"></i>
                                        </button>

                                        <div class="text-center">
                                            <h3 class="text-base font-bold text-[var(--student-document-text-primary)] capitalize" x-text="currentMonth"></h3>
                                            <p class="text-xs text-[var(--student-document-text-secondary)]" x-text="currentYear"></p>
                                        </div>

                                        <button @click="nextMonth()"
                                                class="w-8 h-8 flex items-center justify-center rounded-lg text-[var(--student-document-text-primary)] transition-transform duration-200 hover:scale-125">
                                            <i class="fas fa-chevron-right text-xs"></i>
                                        </button>
                                    </div>
                                    
                                    <!-- Leyenda -->
                                    <div class="flex flex-wrap justify-center gap-x-3 gap-y-1 text-xs">
                                        <div class="flex items-center gap-1">
                                            <div class="w-2 h-2 rounded-full bg-[var(--student-document-calendar-approved)]"></div>
                                            <span class="text-[var(--student-document-text-primary)]">Aprobado</span>
                                        </div>
                                        <div class="flex items-center gap-1">
                                            <div class="w-2 h-2 rounded-full bg-[var(--student-document-calendar-review)]"></div>
                                            <span class="text-[var(--student-document-text-primary)]">En revisión</span>
                                        </div>
                                        <div class="flex items-center gap-1">
                                            <div class="w-2 h-2 rounded-full bg-[var(--student-document-calendar-rejected)]"></div>
                                            <span class="text-[var(--student-document-text-primary)]">Rechazado</span>
                                        </div>
                                        <div class="flex items-center gap-1">
                                            <div class="w-2 h-2 rounded-full bg-[var(--student-document-calendar-pending)]"></div>
                                            <span class="text-[var(--student-document-text-primary)]">Pendiente</span>
                                        </div>
                                        <div class="flex items-center gap-1">
                                            <div class="w-2 h-2 rounded-full bg-[var(--student-document-calendar-expired)]"></div>
                                            <span class="text-[var(--student-document-text-primary)]">Vencido</span>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Grid del calendario -->
                                <div class="p-4">
                                    <div class="grid grid-cols-7 gap-1 mb-2">
                                        <div class="text-center text-xs font-bold text-[var(--student-document-text-primary)] py-2">Dom</div>
                                        <div class="text-center text-xs font-bold text-[var(--student-document-text-primary)] py-2">Lun</div>
                                        <div class="text-center text-xs font-bold text-[var(--student-document-text-primary)] py-2">Mar</div>
                                        <div class="text-center text-xs font-bold text-[var(--student-document-text-primary)] py-2">Mié</div>
                                        <div class="text-center text-xs font-bold text-[var(--student-document-text-primary)] py-2">Jue</div>
                                        <div class="text-center text-xs font-bold text-[var(--student-document-text-primary)] py-2">Vie</div>
                                        <div class="text-center text-xs font-bold text-[var(--student-document-text-primary)] py-2">Sáb</div>
                                    </div>
                                    
                                    <div class="grid grid-cols-7 gap-1">
                                        <template x-for="(day, index) in daysInMonth" :key="index">
                                            <div>
                                                <button 
                                                    x-show="day !== null"
                                                    @click="selectDay(day)"
                                                    :class="{
                                                        'bg-[var(--student-document-calendar-selected-day-bg)] border-2 border-[var(--student-document-calendar-selected-day-border)]': day?.isToday,
                                                        'hover:bg-[var(--student-document-calendar-selected-day-hover)]': day?.events.length === 0,
                                                        'cursor-pointer hover:scale-105': day?.events.length > 0,
                                                        'opacity-50': day?.events.length === 0
                                                    }"
                                                    class="w-full aspect-square flex flex-col items-center justify-center rounded-md transition-all relative group">
                                                    <span class="text-sm font-medium text-[var(--student-document-text-primary)]" x-text="day?.day"></span>
                                                    
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
                                     class="border-t border-[var(--student-document-calendar-selected-day-content-border)] p-4 bg-gradient-to-br from-[var(--student-document-calendar-selected-day-content-bg)] to-[var(--student-document-calendar-selected-day-content-bg-2)]">
                                    <div class="flex items-center justify-between mb-3">
                                        <h4 class="font-bold text-[var(--student-document-text-primary)] text-sm">
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
                                                class="w-8 h-8 flex items-center justify-center rounded-full text-[var(--student-document-text-secondary)] hover:text-[var(--student-document-calendar-selected-day-content-text-hover)] transition">
                                            <i class="fas fa-times text-base"></i>
                                        </button>

                                    </div>

                                    <div class="space-y-2 max-h-48 overflow-y-auto sidebar-scroll">
                                        <template x-for="event in selectedEvents" :key="event.doc.id">
                                            <div class="p-3 rounded-lg transition-all hover:shadow-md bg-[var(--student-document-calendar-selected-day-content-card)]">
                                                <p class="text-xs font-semibold text-[var(--student-document-text-primary)]" x-text="event.doc.name"></p>
                                                <div class="flex items-center justify-between mt-1">
                                                    <span class="text-[10px] font-bold"
                                                        :class="{
                                                            'text-[var(--student-document-calendar-approved)]': event.status === 'revisado',
                                                            'text-[var(--student-document-calendar-rejected)]': event.status === 'rechazado',
                                                            'text-[var(--student-document-calendar-expired)]': event.isExpired && !event.hasFile && event.status !== 'revisado',
                                                            'text-[var(--student-document-calendar-review)]': event.status === 'en_revision' && event.hasFile,
                                                            'text-[var(--student-document-calendar-pending)]': event.status === 'en_revision' && !event.hasFile && !event.isExpired
                                                        }"
                                                        x-text="(
                                                            () => {
                                                                if (event.status === 'revisado') return 'APROBADO';
                                                                if (event.status === 'rechazado') return 'RECHAZADO';
                                                                if (event.isExpired && !event.hasFile) return 'VENCIDO';
                                                                if (event.status === 'en_revision' && event.hasFile) return 'EN REVISIÓN';
                                                                if (event.status === 'en_revision' && !event.hasFile) return 'PENDIENTE';
                                                                return 'PENDIENTE';
                                                            }
                                                        )()"
                                                    ></span>
                                                </div>
                                            </div>
                                        </template>
                                    </div>
                                </div>
                            </div>

                            <!-- Resumen de alertas -->
                            <div class="bg-[var(--student-document-alert-bg)] rounded-xl shadow-lg overflow-hidden p-5">
                                <div class="flex items-center gap-3 mb-4">
                                    <div>
                                        <h3 class="font-bold text-[var(--student-document-text-primary)]">Resumen</h3>
                                        <p class="text-xs text-[var(--student-document-text-secondary)]">Estado de tus entregas</p>
                                    </div>
                                </div>

                                <div class="space-y-3">
                                    <!-- Por vencer -->
                                    <div class="flex items-center justify-between p-3 rounded-lg">
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 bg-[var(--student-document-alert-pending-border)] rounded-lg flex items-center justify-center">
                                                <i class="fas fa-clock text-[var(--student-document-text-primary)] text-sm"></i>
                                            </div>
                                            <div>
                                                <p class="text-sm font-bold text-[var(--student-document-text-primary)]">Por vencer</p>
                                                <p class="text-xs text-[var(--student-document-text-secondary)]">Próximos 7 días</p>
                                            </div>
                                        </div>
                                        <span class="text-2xl font-bold text-[var(--student-document-alert-pending-border)]">
                                            {{ count($upcomingDocs) }}
                                        </span>
                                    </div>
                                    
                                    <!-- Rechazados -->
                                    <div class="flex items-center justify-between p-3 rounded-lg">
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 bg-[var(--student-document-alert-rejected-border)] rounded-lg flex items-center justify-center">
                                                <i class="fas fa-times text-[var(--student-document-text-primary)] text-sm"></i>
                                            </div>
                                            <div>
                                                <p class="text-sm font-bold text-[var(--student-document-text-primary)]">Rechazados</p>
                                                <p class="text-xs text-[var(--student-document-text-secondary)]">Requiere corrección</p>
                                            </div>
                                        </div>
                                        <span class="text-2xl font-bold text-[var(--student-document-alert-rejected-border)]">
                                            {{ $rejectedCount }}
                                        </span>
                                    </div>

                                    <!-- En Revisión -->
                                    <div class="flex items-center justify-between p-3 rounded-lg ">
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 bg-[var(--student-document-alert-review-border)] rounded-lg flex items-center justify-center">
                                                <i class="fas fa-hourglass-half text-[var(--student-document-text-primary)] text-sm"></i>
                                            </div>
                                            <div>
                                                <p class="text-sm font-bold text-[var(--student-document-text-primary)]">En Revisión</p>
                                                <p class="text-xs text-[var(--student-document-text-secondary)]">Pendiente de revisión</p>
                                            </div>
                                        </div>
                                        <span class="text-2xl font-bold text-[var(--student-document-alert-review-border)]">
                                            {{ $reviewCount }}
                                        </span>
                                    </div>

                                    <!-- Aprobados -->
                                    <div class="flex items-center justify-between p-3 rounded-lg">
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 bg-[var(--student-document-alert-approved-border)] rounded-lg flex items-center justify-center">
                                                <i class="fas fa-check text-[var(--student-document-text-primary)] text-sm"></i>
                                            </div>
                                            <div>
                                                <p class="text-sm font-bold text-[var(--student-document-text-primary)]">Aprobados</p>
                                                <p class="text-xs text-[var(--student-document-text-secondary)]">Finalizados</p>
                                            </div>
                                        </div>
                                        <span class="text-2xl font-bold text-[var(--student-document-alert-approved-border)]">
                                            {{ $approvedCount }}
                                        </span>
                                    </div>

                                    <!-- Vencidos -->
                                    <div class="flex items-center justify-between p-3  rounded-lg"
                                        x-data="{ expiredDocs: {{ json_encode($calendarEvents) }} }">
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 bg-[var(--student-document-alert-expired-border)] rounded-lg flex items-center justify-center">
                                                <i class="fas fa-exclamation text-[var(--student-document-text-primary)] text-sm"></i>
                                            </div>
                                            <div>
                                                <p class="text-sm font-bold text-[var(--student-document-text-primary)]">Vencidos</p>
                                                <p class="text-xs text-[var(--student-document-text-secondary)]">Requiere acción</p>
                                            </div>
                                        </div>
                                        <span class="text-2xl font-bold text-[var(--student-document-alert-expired-border)]"
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

    <!-- Toast Notifications Container -->
    <div class="fixed top-4 right-4 z-50 space-y-3" 
         x-data="{ 
             notifications: [],
             addNotification(type, message) {
                 const id = Date.now();
                 this.notifications.push({ id, type, message });
                 setTimeout(() => {
                     this.removeNotification(id);
                 }, 5000);
             },
             removeNotification(id) {
                 this.notifications = this.notifications.filter(n => n.id !== id);
             }
         }"
         @notify.window="addNotification($event.detail.type, $event.detail.message)"
         id="toast-container">
        
        <!-- Notificación inicial de sesión (si existe) -->
        @if (session()->has('message'))
            <div x-data="{ show: true }" 
                 x-show="show"
                 x-init="setTimeout(() => show = false, 5000)"
                 x-transition:enter="transform transition ease-out duration-300"
                 x-transition:enter-start="translate-x-full opacity-0"
                 x-transition:enter-end="translate-x-0 opacity-100"
                 x-transition:leave="transform transition ease-in duration-200"
                 x-transition:leave-start="translate-x-0 opacity-100"
                 x-transition:leave-end="translate-x-full opacity-0"
                 class="bg-white dark:bg-gray-800 rounded-xl shadow-2xl border-l-4 border-green-500 p-4 max-w-md flex items-start gap-3 backdrop-blur-sm">
                <div class="flex-shrink-0 w-10 h-10 bg-green-100 dark:bg-green-900/30 rounded-lg flex items-center justify-center">
                    <i class="fas fa-check-circle text-green-600 dark:text-green-400 text-xl"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <h4 class="font-bold text-gray-900 dark:text-white text-sm mb-1">¡Éxito!</h4>
                    <p class="text-gray-600 dark:text-gray-300 text-sm">{{ session('message') }}</p>
                </div>
                <button @click="show = false" class="flex-shrink-0 text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 transition">
                    <i class="fas fa-times text-sm"></i>
                </button>
            </div>
        @endif

        @if (session()->has('error'))
            <div x-data="{ show: true }" 
                 x-show="show"
                 x-init="setTimeout(() => show = false, 5000)"
                 x-transition:enter="transform transition ease-out duration-300"
                 x-transition:enter-start="translate-x-full opacity-0"
                 x-transition:enter-end="translate-x-0 opacity-100"
                 x-transition:leave="transform transition ease-in duration-200"
                 x-transition:leave-start="translate-x-0 opacity-100"
                 x-transition:leave-end="translate-x-full opacity-0"
                 class="bg-white dark:bg-gray-800 rounded-xl shadow-2xl border-l-4 border-red-500 p-4 max-w-md flex items-start gap-3 backdrop-blur-sm">
                <div class="flex-shrink-0 w-10 h-10 bg-red-100 dark:bg-red-900/30 rounded-lg flex items-center justify-center">
                    <i class="fas fa-times-circle text-red-600 dark:text-red-400 text-xl"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <h4 class="font-bold text-gray-900 dark:text-white text-sm mb-1">Error</h4>
                    <p class="text-gray-600 dark:text-gray-300 text-sm">{{ session('error') }}</p>
                </div>
                <button @click="show = false" class="flex-shrink-0 text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 transition">
                    <i class="fas fa-times text-sm"></i>
                </button>
            </div>
        @endif

        <!-- Notificaciones dinámicas -->
        <template x-for="notification in notifications" :key="notification.id">
            <div x-show="true"
                 x-transition:enter="transform transition ease-out duration-300"
                 x-transition:enter-start="translate-x-full opacity-0"
                 x-transition:enter-end="translate-x-0 opacity-100"
                 x-transition:leave="transform transition ease-in duration-200"
                 x-transition:leave-start="translate-x-0 opacity-100"
                 x-transition:leave-end="translate-x-full opacity-0"
                 :class="{
                     'border-green-500': notification.type === 'success',
                     'border-red-500': notification.type === 'error',
                     'border-blue-500': notification.type === 'info',
                     'border-yellow-500': notification.type === 'warning'
                 }"
                 class="bg-white dark:bg-gray-800 rounded-xl shadow-2xl border-l-4 p-4 max-w-md flex items-start gap-3 backdrop-blur-sm">
                <div class="flex-shrink-0 w-10 h-10 rounded-lg flex items-center justify-center"
                     :class="{
                         'bg-green-100 dark:bg-green-900/30': notification.type === 'success',
                         'bg-red-100 dark:bg-red-900/30': notification.type === 'error',
                         'bg-blue-100 dark:bg-blue-900/30': notification.type === 'info',
                         'bg-yellow-100 dark:bg-yellow-900/30': notification.type === 'warning'
                     }">
                    <i class="text-xl"
                       :class="{
                           'fas fa-check-circle text-green-600 dark:text-green-400': notification.type === 'success',
                           'fas fa-times-circle text-red-600 dark:text-red-400': notification.type === 'error',
                           'fas fa-info-circle text-blue-600 dark:text-blue-400': notification.type === 'info',
                           'fas fa-exclamation-triangle text-yellow-600 dark:text-yellow-400': notification.type === 'warning'
                       }"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <h4 class="font-bold text-gray-900 dark:text-white text-sm mb-1"
                        x-text="notification.type === 'success' ? '¡Éxito!' : (notification.type === 'error' ? 'Error' : (notification.type === 'warning' ? 'Advertencia' : 'Información'))"></h4>
                    <p class="text-gray-600 dark:text-gray-300 text-sm" x-text="notification.message"></p>
                </div>
                <button @click="removeNotification(notification.id)" 
                        class="flex-shrink-0 text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 transition">
                    <i class="fas fa-times text-sm"></i>
                </button>
            </div>
        </template>
    </div>

    <style>
        /* Scroll personalizado */
        .sidebar-scroll {
            scrollbar-width: thin;
            scrollbar-color: rgba(60, 120, 199, 0.5) transparent;
        }

        .sidebar-scroll::-webkit-scrollbar {
            width: 8px;
        }

        .sidebar-scroll::-webkit-scrollbar-track {
            background: transparent;
            border-radius: 10px;
        }

        .sidebar-scroll::-webkit-scrollbar-thumb {
            background: linear-gradient(180deg, rgba(59, 130, 246, 0.6), rgba(147, 51, 234, 0.6));
            border-radius: 10px;
            border: 2px solid transparent;
            background-clip: padding-box;
        }

        .sidebar-scroll::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(180deg, rgba(59, 130, 246, 0.8), rgba(147, 51, 234, 0.8));
            background-clip: padding-box;
        }

        .sidebar-scroll {
            scroll-behavior: smooth;
        }

        /* Animación para toast notifications */
        @keyframes slideInRight {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        @keyframes slideOutRight {
            from {
                transform: translateX(0);
                opacity: 1;
            }
            to {
                transform: translateX(100%);
                opacity: 0;
            }
        }

        /* Responsive para toast en móviles */
        @media (max-width: 640px) {
            #toast-container {
                left: 1rem;
                right: 1rem;
                top: 1rem;
            }
            
            #toast-container > div {
                max-width: 100%;
            }
        }
    </style>

    <!-- Modales -->
    @if($previewPath)
    <flux:modal wire:model="previewPath" class="md:w-4/5 lg:w-3/4">
        <div class="flex flex-col space-y-6">
            @php
                $cleanName = $previewName;
                $pos = strpos($cleanName, '_');
                if ($pos !== false) {
                    $cleanName = substr($cleanName, 0, $pos);
                }
                $ext = strtolower(pathinfo($previewPath, PATHINFO_EXTENSION));
            @endphp

            <div class="flex items-center justify-between pb-4 border-b dark:border-gray-700">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-purple-500 rounded-xl flex items-center justify-center">
                        <i class="fas fa-file-pdf text-white text-xl"></i>
                    </div>
                    <div>
                        <flux:heading size="lg" class="text-gray-900 dark:text-white">{{ $cleanName }}</flux:heading>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Vista previa del documento</p>
                    </div>
                </div>
                <button wire:click="$set('previewPath', null)" class="w-10 h-10 flex items-center justify-center rounded-lg bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600 transition">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            @if($ext === 'pdf')
                <iframe src="{{ asset('storage/'.$previewPath) }}" class="w-full h-[70vh] rounded-xl border-2 border-gray-200 dark:border-gray-700"></iframe>
            @else
                <div class="text-center py-16">
                    <div class="inline-flex items-center justify-center w-20 h-20 bg-gray-100 dark:bg-gray-800 rounded-2xl mb-4">
                        <i class="fas fa-file-download text-4xl text-gray-400"></i>
                    </div>
                    <p class="text-gray-700 dark:text-gray-300 mb-6 text-lg">Este archivo no se puede previsualizar</p>
                    <a href="{{ asset('storage/'.$previewPath) }}" target="_blank" 
                       class="inline-flex items-center gap-3 px-6 py-3 bg-gradient-to-r from-blue-500 to-purple-500 text-white rounded-xl hover:from-blue-600 hover:to-purple-600 transition font-bold shadow-lg">
                        <i class="fas fa-download"></i>
                        Descargar {{ $cleanName }}
                    </a>
                </div>
            @endif

            <div class="flex justify-end gap-3 pt-4 border-t dark:border-gray-700">
                <a href="{{ asset('storage/'.$previewPath) }}" download 
                   class="inline-flex items-center gap-2 px-5 py-2.5 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition font-medium">
                    <i class="fas fa-download"></i>
                    Descargar
                </a>
                <flux:button variant="primary" wire:click="$set('previewPath', null)">
                    Cerrar
                </flux:button>
            </div>
        </div>
    </flux:modal>
    @endif

    @if($isCommentsModalOpen && $selectedDocument)
    <flux:modal wire:model="isCommentsModalOpen" class="md:w-2/3 lg:w-1/2">
        <div class="flex flex-col space-y-6">
            <div class="flex items-center gap-4 pb-4 border-b dark:border-gray-700">
                <div class="w-14 h-14 bg-gradient-to-br from-red-500 to-pink-500 rounded-xl flex items-center justify-center">
                    <i class="fas fa-comment-dots text-white text-2xl"></i>
                </div>
                <div>
                    <flux:heading size="lg" class="text-gray-900 dark:text-white">
                        Observaciones del Revisor
                    </flux:heading>
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        Comentarios sobre tu documento
                    </p>
                </div>
            </div>

            <div class="max-h-96 overflow-y-auto sidebar-scroll">
                @if($selectedDocument->comments)
                    <div class="bg-gradient-to-br from-red-50 to-pink-50 dark:from-red-900/20 dark:to-pink-900/20 border-2 border-red-200 dark:border-red-800 rounded-xl p-6">
                        <div class="whitespace-pre-line text-gray-800 dark:text-gray-200 leading-relaxed">
                            {{ $selectedDocument->comments }}
                        </div>
                    </div>
                @else
                    <div class="text-center py-12">
                        <div class="inline-flex items-center justify-center w-16 h-16 bg-gray-100 dark:bg-gray-800 rounded-2xl mb-4">
                            <i class="fas fa-inbox text-3xl text-gray-400"></i>
                        </div>
                        <p class="text-gray-500 dark:text-gray-400">
                            No hay comentarios para este documento
                        </p>
                    </div>
                @endif
            </div>

            <div class="flex justify-end gap-3 pt-4 border-t dark:border-gray-700">
                <flux:button
                    variant="ghost"
                    wire:click="$set('isCommentsModalOpen', false)">
                    Cerrar
                </flux:button>
            </div>
        </div>
    </flux:modal>
    @endif

</div>