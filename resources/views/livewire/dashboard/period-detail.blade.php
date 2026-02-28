<div class="space-y-8 min-h-screen p-6">

    {{-- Header --}}
    <div class="w-full mb-8 rounded-xl shadow-sm p-6"
        style="background-color: var(--period-detail-bg);">

        <button
            wire:click="goBack"
            class="flex items-center gap-2 text-gray-400 hover:text-white mb-6 transition-all duration-200 group"
            style="color: var(--period-detail-text-secondary);"
        >
            <svg class="w-5 h-5 group-hover:-translate-x-1 transition-transform"
                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M15 19l-7-7 7-7" />
            </svg>
            <span class="font-medium" style="color: var(--period-detail-text-primary);">Volver a periodos</span>
        </button>

        <div class="flex items-start justify-between w-full">
            <div class="flex-1">
                <x-auth-header
                    title="{{ $period->name }}"
                    description="{{ \Carbon\Carbon::parse($period->start_date)->translatedFormat('d M') }} - {{ \Carbon\Carbon::parse($period->end_date)->translatedFormat('d M Y') }}"
                    :center="false"
                />
            </div>

            @if($period->is_active)
                <div class="bg-emerald-500/10 backdrop-blur-sm px-5 py-2.5 rounded-full border"
                    style="border-color: var(--period-detail-status-approved-border); background-color: var(--period-detail-status-approved-icon-bg);">
                    <div class="flex items-center gap-2">
                        <span class="relative flex h-2 w-2">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full"
                                style="background-color: var(--period-detail-status-approved-icon-bg); opacity: 0.75;"></span>
                            <span class="relative inline-flex rounded-full h-2 w-2"
                                style="background-color: var(--period-detail-status-approved-icon-color);"></span>
                        </span>
                        <span class="font-semibold text-sm"
                            style="color: var(--period-detail-status-approved-icon-color);">Activo</span>
                    </div>
                </div>
            @endif
        </div>

    </div>


    {{-- Quick Stats Bar --}}
    <div class="w-full mb-6">
        <div class="grid gap-4 [grid-template-columns:repeat(auto-fit,minmax(220px,1fr))]">

            <!-- Total Estudiantes -->
            <div class="h-full rounded-xl p-5 shadow-lg bg-[var(--period-detail-bg)]">
                <div class="flex items-center justify-between h-full">
                    <div>
                        <p class="text-sm mb-1" style="color: var(--period-detail-text-secondary);">Total Estudiantes</p>
                        <p class="text-3xl font-bold" style="color: var(--period-detail-text-primary);">
                            {{ $period->students->count() }}
                        </p>
                    </div>
                    <div class="w-12 h-12 rounded-xl flex items-center justify-center" 
                         style="background-color: var(--period-detail-icon-bg);">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                             style="color: var(--period-detail-icon-text);">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Aprobados -->
            <div class="h-full rounded-xl p-5 shadow-lg bg-[var(--period-detail-bg)]">
                <div class="flex items-center justify-between h-full">
                    <div>
                        <p class="text-sm mb-1" style="color: var(--period-detail-text-secondary);">Aprobados</p>
                        <p class="text-3xl font-bold" style="color: var(--period-detail-text-primary);">
                            {{ $period->students->where('status','aprobado')->count() }}
                        </p>
                    </div>
                    <div class="w-12 h-12 rounded-xl flex items-center justify-center" 
                         style="background-color: rgba(16, 185, 129, 0.2);">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                             style="color: var(--period-detail-status-approved-icon-color);">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Pendientes -->
            <div class="h-full rounded-xl p-5 shadow-lg bg-[var(--period-detail-bg)]">
                <div class="flex items-center justify-between h-full">
                    <div>
                        <p class="text-sm mb-1" style="color: var(--period-detail-text-secondary);">Pendientes</p>
                        <p class="text-3xl font-bold" style="color: var(--period-detail-text-primary);">
                            {{ $period->students->where('status','pendiente')->count() }}
                        </p>
                    </div>
                    <div class="w-12 h-12 rounded-xl flex items-center justify-center" 
                         style="background-color: rgba(232, 210, 50, 0.2);">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                             style="color: var(--period-detail-status-pending);">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Rechazados -->
            <div class="h-full rounded-xl p-5 shadow-lg bg-[var(--period-detail-bg)]">
                <div class="flex items-center justify-between h-full">
                    <div>
                        <p class="text-sm mb-1" style="color: var(--period-detail-text-secondary);">Rechazados</p>
                        <p class="text-3xl font-bold" style="color: var(--period-detail-text-primary);">
                            {{ $period->students->where('status','rechazado')->count() }}
                        </p>
                    </div>
                    <div class="w-12 h-12 rounded-xl flex items-center justify-center" 
                         style="background-color: rgba(239, 68, 68, 0.2);">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                             style="color: var(--period-detail-status-rejected-text);">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Documentos Base -->
            <div class="h-full rounded-xl p-5 shadow-lg bg-[var(--period-detail-bg)]">
                <div class="flex items-center justify-between h-full">
                    <div>
                        <p class="text-sm mb-1" style="color: var(--period-detail-text-secondary);">Documentos Base</p>
                        <p class="text-3xl font-bold" style="color: var(--period-detail-text-primary);">
                            {{ $period->files->count() }}
                        </p>
                    </div>
                    <div class="w-12 h-12 rounded-xl flex items-center justify-center" 
                         style="background-color: rgba(59, 130, 246, 0.2);">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                             style="color: var(--period-detail-status-normal-text);">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                </div>
            </div>

        </div>
    </div>

    {{-- Navigation Tabs --}}
    <div class="w-full mb-6">
        <div class="backdrop-blur-xl rounded-2xl p-2 inline-flex gap-2" 
             style="background-color: var(--period-detail-bg);">
            @foreach($tabs as $key => $data)
                <button
                    wire:click="setTab('{{ $key }}')"
                    class="flex items-center gap-2 px-6 py-3 font-medium rounded-xl transition-all duration-200
                    {{ $activeTab === $key ? '' : '' }}"
                    style="{{ $activeTab === $key 
                        ? 'background-color: var(--period-detail-btn-primary-bg); color: var(--period-detail-btn-primary-text); box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.3);' 
                        : 'color: var(--period-detail-text-secondary);' }}"
                    onmouseover="if(this.style.backgroundColor !== 'var(--period-detail-btn-primary-bg)') { this.style.backgroundColor = 'var(--period-detail-card-bg)'; this.style.color = 'var(--period-detail-text-primary)'; }"
                    onmouseout="if('{{ $activeTab }}' !== '{{ $key }}') { this.style.backgroundColor = 'transparent'; this.style.color = 'var(--period-detail-text-secondary)'; }"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="{{ $data['icon'] }}"/>
                    </svg>

                    {{ $data['label'] }}
                </button>
            @endforeach
        </div>
    </div>

    {{-- Content Area --}}
    <div class="w-full">
        
        {{-- GESTIÓN DE ESTUDIANTES --}}
        @if($activeTab === 'estudiantes')
            <div class="backdrop-blur-xl rounded-2xl shadow-2xl overflow-hidden" 
                 style="background-color: var(--period-detail-bg); 
                        border: 1px solid var(--period-detail-border);">

                {{-- Header --}}
                <div class="p-6" style="border-bottom: 1px solid var(--period-detail-border);">
                    <h2 class="text-2xl font-bold mb-2" style="color: var(--period-detail-text-primary);">Gestión de Estudiantes</h2>
                    <p style="color: var(--period-detail-text-secondary);">Revisa el perfil de los estudiantes</p>

                    <div class="mt-4 flex flex-col lg:flex-row gap-3 items-center">
                        {{-- Buscador --}}
                        <div class="flex-1 min-w-0">
                            <div class="relative w-full">
                                <!-- Ícono de búsqueda -->
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                        style="color: var(--period-detail-text-primary);">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                    </svg>
                                </div>

                                <!-- Input de búsqueda -->
                                <input
                                    type="text"
                                    wire:model.live="search"
                                    placeholder="Buscar por nombre o número de control..."
                                    class="w-full pl-10 pr-4 py-2.5 rounded-lg text-sm transition-all focus:ring-2 focus:outline-none"
                                    style="
                                        background-color: var(--period-detail-card-bg);
                                        color: var(--period-detail-text-primary);
                                        border: 1px solid var(--period-detail-border);
                                    "
                                />
                            </div>
                        </div>

                        {{-- 📋 Filtro de estado --}}
                        <flux:select
                            wire:model.live="statusFilterStudents"
                            class="w-36 lg:w-48 px-3 py-2.5 rounded-lg text-sm font-medium transition-all"
                            style="
                                background-color: var(--period-detail-card-bg);
                                color: var(--period-detail-text-primary);
                                border: 1px solid var(--period-detail-border);
                            "
                        >
                            <option value="">Todos</option>
                            <option value="pending">Pendientes</option>
                            <option value="approved">Aprobados</option>
                            <option value="rejected">Rechazados</option>
                        </flux:select>

                    </div>

                </div>

                {{-- Tabla --}}
                <div class="overflow-x-auto rounded-2xl ">
                    <table class="w-full">
                        <thead style="background: linear-gradient(135deg, var(--period-detail-card-header-approved-bg) 0%, var(--period-detail-card-header-approved-bg-2) 100%);">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-semibold uppercase" 
                                    style="color: var(--period-detail-text-secondary);">Nombre</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold uppercase" 
                                    style="color: var(--period-detail-text-secondary);">Avance Reticular</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold uppercase" 
                                    style="color: var(--period-detail-text-secondary);">Estatus</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold uppercase" 
                                    style="color: var(--period-detail-text-secondary);">Acciones</th>
                            </tr>
                        </thead>

                        <tbody style="border-top: 1px solid var(--period-detail-border);">
                            @forelse($students as $student)
                            <tr class="transition hover-row" 
                                onmouseover="this.style.backgroundColor='var(--period-detail-card-bg)'"
                                onmouseout="this.style.backgroundColor='transparent'">

                                {{-- Nombre --}}
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div>
                                            <p class="font-medium" style="color: var(--period-detail-text-primary);">
                                                {{ $student->name }} {{ $student->last_name_paterno }} {{ $student->last_name_materno }}
                                            </p>
                                            <p class="text-xs" style="color: var(--period-detail-text-secondary);">{{ $student->career->name }}</p>
                                            <p class="text-xs" style="color: var(--period-detail-text-secondary);">{{ $student->control_number }}</p>
                                        </div>
                                    </div>
                                </td>

                                {{-- Avance --}}
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        
                                        <span class="text-sm min-w-[3rem] text-right" 
                                              style="color: var(--period-detail-text-primary);">
                                            {{ $student->reticular_progress }}%
                                        </span>
                                    </div>
                                </td>

                                {{-- Estatus --}}
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-3 py-1 text-xs font-semibold rounded-full" style="{{ $student->status_style }}">
                                        {{ $student->status_label }}
                                    </span>
                                </td>

                                {{-- Acciones --}}
                                <td class="px-6 py-4">
                                    <button
                                        wire:click="viewDetails({{ $student->id }})"
                                        class="px-3 py-2 text-xs rounded-lg transition flex items-center gap-2"
                                        style="background-color: var(--period-detail-btn-view-perfil-bg); 
                                               color: var(--period-detail-btn-view-perfil-text);"
                                        onmouseover="this.style.backgroundColor='var(--period-detail-btn-view-perfil-hover)'; this.style.transform='translateY(-2px)'; this.style.color='var(--period-detail-btn-view-perfil-text-hover)';"
                                        onmouseout="this.style.backgroundColor='var(--period-detail-btn-view-perfil-bg)'; this.style.transform='translateY(0)'; this.style.color='var(--period-detail-btn-view-perfil-text)';"
                                    >

                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M2.458 12C3.732 7.943 7.523 5 12 5
                                                c4.478 0 8.268 2.943 9.542 7
                                                -1.274 4.057-5.064 7-9.542 7
                                                -4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                        Ver perfil
                                    </button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="px-6 py-12 text-center" style="color: var(--period-detail-text-secondary);">
                                    No hay estudiantes registrados.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Paginación --}}
                <div class="p-4" style="border-top: 1px solid var(--period-detail-border);">
                    {{ $students->links() }}
                </div>
            </div>
        @endif

        {{-- DOCUMENTOS BASE --}}
        @if($activeTab === 'documentos')
            <div class="space-y-6">

                {{-- ================== CREAR / EDITAR DOCUMENTO ================== --}}
                <div class="backdrop-blur-xl rounded-2xl shadow-2xl overflow-hidden" 
                     style="background-color: var(--period-detail-bg); ">
                        {{-- Header --}}
                        <div class="p-6" 
                            style="background: linear-gradient(135deg, var(--period-detail-card-header-bg) 0%, var(--period-detail-card-header-bg-2) 100%);
                                    border-bottom: 1px solid var(--period-detail-border);">
                            <div class="flex items-center gap-3">
                                <div class="w-12 h-12 rounded-xl flex items-center justify-center shadow-lg"
                                    style="background: linear-gradient(135deg, var(--period-detail-brand-primary) 0%, var(--period-detail-brand-secondary) 100%); color: var(--period-detail-text-icon);">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        @if($editingDocumentId)
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        @else
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 4v16m8-8H4"/>
                                        @endif
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <h2 class="text-xl font-bold" style="color: var(--period-detail-text-primary);">
                                        {{ $editingDocumentId ? 'Editar Documento Base' : 'Crear Nuevo Documento Base' }}
                                    </h2>
                                    <p class="text-sm" style="color: var(--period-detail-text-secondary);">
                                        {{ $editingDocumentId ? 'Actualiza la información del documento' : 'Los estudiantes deberán subir este documento' }}
                                    </p>
                                </div>
                                @if($editingDocumentId)
                                    <button wire:click="cancelEditDocument"
                                            class="px-4 py-2 rounded-lg transition-colors text-sm font-medium"
                                            style="background-color: var(--period-detail-content-bg); 
                                                color: var(--period-detail-text-secondary);"
                                            onmouseover="this.style.backgroundColor='var(--period-detail-card-indicator-bg)'"
                                            onmouseout="this.style.backgroundColor='var(--period-detail-content-bg)'">
                                        <svg class="w-4 h-4 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                        Cancelar
                                    </button>
                                @endif
                            </div>
                        </div>

                        {{-- Form --}}
                        <div class="p-6">
                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                                
                                {{-- Nombre del Documento --}}
                                <flux:field class="lg:col-span-2">
                                    <flux:label class="flex items-center">
                                        <svg class="w-4 h-4 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                            style="color: var(--period-detail-brand-primary);">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                        </svg>
                                        <span>Nombre del Documento</span>
                                        <span class="ml-1" style="color: var(--period-detail-status-rejected-text);">*</span>
                                    </flux:label>
                                    <flux:input wire:model.defer="documentName" type="text"
                                        placeholder="Ej: Anexo 10. Plan de Trabajo"
                                        style="background-color: var(--period-detail-card-bg); color: var(--period-detail-text-primary);"
                                        class="w-full px-4 py-3 rounded-xl transition-all"/>
                                    <flux:error name="documentName" />
                                </flux:field>

                                {{-- ============ MODO DE CARGA ============ --}}
                                <div class="lg:col-span-2">
                                    <label class="block text-sm font-semibold mb-3 flex items-center gap-2" 
                                        style="color: var(--period-detail-text-primary);">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                            style="color: var(--period-detail-brand-primary); ">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                                        </svg>
                                        Modo de Carga
                                        <span class="ml-1" style="color: var(--period-detail-status-rejected-text);">*</span>
                                    </label>

                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                        {{-- Bidirectional --}}
                                        <label class="relative cursor-pointer group">
                                            <input type="radio" 
                                                wire:model.live="documentUploadMode" 
                                                value="bidirectional" 
                                                class="peer sr-only"
                                                {{ (!$documentUploadMode || $documentUploadMode === 'bidirectional') ? 'checked' : '' }}>
                                            
                                            <div class="p-4 rounded-xl border-2 transition-all flex h-full peer-checked:border-2"
                                                style="background-color: var(--period-detail-card-bg); border-color: var(--period-detail-border);"
                                                onmouseover="if(!this.previousElementSibling.checked) this.style.borderColor='var(--period-detail-brand-primary)'"
                                                onmouseout="if(!this.previousElementSibling.checked) this.style.borderColor='var(--period-detail-border)'">
                                                
                                                <div class="flex items-stretch gap-3 w-full">
                                                    <div class="upload-mode-icon w-10 h-10 rounded-lg flex items-center justify-center flex-shrink-0"
                                                        style="background-color: var(--period-detail-content-bg);">
                                                        <svg class="w-5 h-5 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                                            style="color: var(--period-detail-text-secondary);">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                                d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                                                        </svg>
                                                    </div>
                                                    <div class="flex-1 flex flex-col justify-between">
                                                        <div>
                                                            <h4 class="font-semibold text-sm mb-1" style="color: var(--period-detail-text-primary);">
                                                                Bidireccional
                                                            </h4>
                                                            <p class="text-xs" style="color: var(--period-detail-text-secondary);">
                                                                Admin y estudiantes pueden subir archivos
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </label>

                                        {{-- User Only --}}
                                        <label class="relative cursor-pointer group">
                                            <input type="radio" 
                                                wire:model.live="documentUploadMode" 
                                                value="user_only" 
                                                class="peer sr-only">
                                            
                                            <div class="p-4 rounded-xl border-2 transition-all flex h-full peer-checked:border-2"
                                                style="background-color: var(--period-detail-card-bg); border-color: var(--period-detail-border);"
                                                onmouseover="if(!this.previousElementSibling.checked) this.style.borderColor='var(--period-detail-brand-primary)'"
                                                onmouseout="if(!this.previousElementSibling.checked) this.style.borderColor='var(--period-detail-border)'">
                                                
                                                <div class="flex items-stretch gap-3 w-full">
                                                    <div class="upload-mode-icon w-10 h-10 rounded-lg flex items-center justify-center flex-shrink-0"
                                                        style="background-color: var(--period-detail-content-bg);">
                                                        <svg class="w-5 h-5 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                                            style="color: var(--period-detail-text-secondary);">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                                        </svg>
                                                    </div>
                                                    <div class="flex-1 flex flex-col justify-between">
                                                        <div>
                                                            <h4 class="font-semibold text-sm mb-1" style="color: var(--period-detail-text-primary);">
                                                                Solo Estudiantes
                                                            </h4>
                                                            <p class="text-xs" style="color: var(--period-detail-text-secondary);">
                                                                Solo estudiantes pueden subir archivos
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </label>

                                        {{-- Admin Only --}}
                                        <label class="relative cursor-pointer group">
                                            <input type="radio" 
                                                wire:model.live="documentUploadMode" 
                                                value="admin_only" 
                                                class="peer sr-only">
                                            
                                            <div class="p-4 rounded-xl border-2 transition-all flex h-full peer-checked:border-2"
                                                style="background-color: var(--period-detail-card-bg); border-color: var(--period-detail-border);"
                                                onmouseover="if(!this.previousElementSibling.checked) this.style.borderColor='var(--period-detail-brand-primary)'"
                                                onmouseout="if(!this.previousElementSibling.checked) this.style.borderColor='var(--period-detail-border)'">
                                                
                                                <div class="flex items-stretch gap-3 w-full">
                                                    <div class="upload-mode-icon w-10 h-10 rounded-lg flex items-center justify-center flex-shrink-0"
                                                        style="background-color: var(--period-detail-content-bg);">
                                                        <svg class="w-5 h-5 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                                            style="color: var(--period-detail-text-secondary);">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                                d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                                        </svg>
                                                    </div>
                                                    <div class="flex-1 flex flex-col justify-between">
                                                        <div>
                                                            <h4 class="font-semibold text-sm mb-1" style="color: var(--period-detail-text-primary);">
                                                                Solo Admin
                                                            </h4>
                                                            <p class="text-xs" style="color: var(--period-detail-text-secondary);">
                                                                Solo administradores pueden subir archivos
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </label>
                                    </div>
                                    <flux:error name="documentUploadMode" />
                                </div>

                                {{-- Fecha Límite y Tamaño Máximo --}}
                                @if($documentUploadMode !== 'admin_only')
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-5 lg:col-span-2">

                                    {{-- Fecha límite --}}                                    
                                    <flux:field>
                                        <flux:label class="flex items-center">
                                            <svg class="w-4 h-4 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                                style="color: var(--period-detail-brand-primary);">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                            </svg>
                                            <span>Fecha Límite</span>
                                                <span class="ml-1" style="color: var(--period-detail-status-rejected-text);">*</span>
                                        </flux:label>
                                        <flux:input 
                                            wire:model.defer="documentDeadline" 
                                            type="date"
                                            min="{{ $period->start_date }}" 
                                            max="{{ $period->end_date }}" 
                                            style="background-color: var(--period-detail-card-bg); color: var(--period-detail-text-primary);"
                                            class="w-full px-4 py-3 rounded-xl transition-all"/>
                                        
                                        <flux:error name="documentDeadline" />    
                                    </flux:field>
                                    
                                    {{-- Tamaño máximo --}}
                                    <flux:field>
                                        <flux:label class="flex items-center">
                                            <svg class="w-4 h-4 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                                style="color: var(--period-detail-brand-primary);">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4"/>
                                            </svg>
                                            <span>Tamaño Máximo (KB)</span>
                                            <span class="ml-1" style="color: var(--period-detail-status-rejected-text);">*</span>
                                        </flux:label>
                                        <flux:input 
                                            wire:model.defer="maxSize" 
                                            type="number" 
                                            placeholder="10240"
                                            min="1"
                                            max="20480"
                                            style="background-color: var(--period-detail-card-bg); color: var(--period-detail-text-primary);"
                                            class="w-full px-4 py-3 rounded-xl transition-all"/>
                                        <flux:error name="maxSize" /> 
                                    </flux:field>
                                </div>
                                @endif

                                <style>
                                    /* Estilos para radio buttons checked */
                                    input[type="radio"]:checked + div {
                                        border-color: var(--period-detail-brand-check) !important;
                                        background: linear-gradient(135deg, rgba(60, 61, 133, 0.05) 0%, rgba(60, 61, 133, 0.05) 100%);
                                    }
                                    input[type="radio"]:checked + div .upload-mode-icon {
                                        background: linear-gradient(135deg, var(--period-detail-brand-check) 0%, var(--period-detail-brand-check) 100%) !important;
                                    }
                                    input[type="radio"]:checked + div .upload-mode-icon svg {
                                        color: var(--period-detail-text-icon) !important;
                                    }
                                </style>

                                {{-- Checkbox de Documento Individual (solo visible si admin_only) --}}
                                @if($documentUploadMode === 'admin_only')
                                    <div class="lg:col-span-2">
                                        <div class="p-5 rounded-xl border-2 transition-all cursor-pointer"
                                            style="background-color: var(--period-detail-card-bg); border-color: var(--period-detail-border);"
                                            wire:click="$toggle('isIndividual')"
                                            onmouseover="this.style.borderColor='var(--period-detail-brand-primary)'"
                                            onmouseout="this.style.borderColor='var(--period-detail-border)')">

                                            <div class="flex items-start gap-4">
                                                {{-- Checkbox Custom --}}
                                                <div class="flex-shrink-0 pt-0.5">
                                                    <input type="checkbox"
                                                        wire:model.live="isIndividual"
                                                        id="isIndividual"
                                                        class="sr-only">

                                                    <label for="isIndividual"
                                                        class="w-6 h-6 rounded-md border-2 flex items-center justify-center cursor-pointer transition-all"
                                                        style="border-color: var(--period-detail-border); 
                                                                {{ $isIndividual ? 'background: linear-gradient(135deg, var(--period-detail-brand-primary) 0%, var(--period-detail-brand-secondary) 100%); border: 0;' : '' }}">
                                                        @if($isIndividual)
                                                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                                                            </svg>
                                                        @endif
                                                    </label>
                                                </div>

                                                {{-- Contenido --}}
                                                <div class="flex-1">
                                                    <div class="flex items-center gap-2 mb-2">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                                            style="color: var(--period-detail-brand-primary);">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                                        </svg>
                                                        <h4 style="color: var(--period-detail-text-primary);">
                                                            Documento Individual
                                                        </h4>
                                                    </div>
                                                    <p class="text-sm leading-relaxed" style="color: var(--period-detail-text-secondary);">
                                                        El administrador podrá subir un archivo específico diferente para cada alumno. En la sección de Revisión de Documentos.
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                {{-- Archivos (solo si no es user_only) --}}
                                @if(!$documentUploadMode || $documentUploadMode !== 'user_only')
                                    {{-- Solo mostrar archivos si NO es admin_only con individual --}}
                                    @if(!($documentUploadMode === 'admin_only' && $isIndividual))
                                        {{-- Archivo principal --}}
                                        <flux:field class="lg:col-span-2">
                                            <flux:label class="flex items-center">
                                                <svg class="w-4 h-4 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                                    style="color: var(--period-detail-brand-primary);">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                                </svg>
                                                <span>Archivo del Documento (Word)</span>
                                            </flux:label>

                                            <input type="file" wire:model="documentFile" accept=".doc,.docx" class="hidden" id="documentFile">

                                            <label for="documentFile"
                                                class="group flex items-center justify-center w-full px-6 py-8 border-2 border-dashed rounded-xl cursor-pointer transition-all"
                                                style="background-color: var(--period-detail-card-bg); border-color: var(--period-detail-border); color: var(--period-detail-text-primary);"
                                                onmouseover="this.style.borderColor='var(--period-detail-brand-primary)';"
                                                onmouseout="this.style.borderColor='var(--period-detail-border)';">
                                                <div class="text-center">
                                                    <svg class="w-12 h-12 mx-auto mb-3 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                                        style="color: var(--period-detail-text-secondary);">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                                                    </svg>
                                                    <p class="text-sm font-medium transition-colors" style="color: var(--period-detail-text-secondary);">
                                                        Haz clic para seleccionar
                                                    </p>
                                                </div>
                                            </label>

                                            @if ($documentFile)
                                                <div class="mt-3 p-3 rounded-lg flex items-center gap-3" 
                                                    style="background-color: var(--period-detail-card-bg); 
                                                            border: 1px solid var(--period-detail-border);">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                                        style="color: var(--period-detail-text-secondary);">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                                                    </svg>
                                                    <span class="text-sm flex-1" style="color: var(--period-detail-text-secondary);">{{ $documentFile->getClientOriginalName() }}</span>
                                                    <button wire:click="$set('documentFile', null)" 
                                                            type="button"
                                                            style="color: var(--period-detail-text-secondary);"
                                                            onmouseover="this.style.opacity='0.7'"
                                                            onmouseout="this.style.opacity='1'">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                                        </svg>
                                                    </button>
                                                </div>
                                            @elseif($editingDocumentId && $period->files->find($editingDocumentId)?->name_file && !$removeDocumentFile)
                                                <div class="mt-3 p-3 rounded-lg flex items-center gap-3" 
                                                    style="background-color: var(--period-detail-card-bg); border: 1px solid var(--period-detail-border);">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                                        style="color: var(--period-detail-text-secondary);">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                                    </svg>
                                                    <span class="text-sm flex-1" style="color: var(--period-detail-text-secondary);">
                                                        Archivo actual: {{ $period->files->find($editingDocumentId)->name_file }}
                                                    </span>
                                                    <button wire:click="removeExistingDocumentFile" 
                                                            type="button"
                                                            class="p-1 rounded-full transition-all"
                                                            style="color: var(--period-detail-text-secondary);"
                                                            onmouseover="this.style.opacity='0.8'"
                                                            onmouseout="this.style.opacity='1'">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                                        </svg>
                                                    </button>

                                                </div>
                                            @elseif($editingDocumentId && $removeDocumentFile)
                                                <div class="mt-3 p-3 rounded-lg flex items-center gap-3" 
                                                    style="background-color: rgba(239, 68, 68, 0.1); border: 1px solid rgba(239, 68, 68, 0.3);">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                                        style="color: rgb(239, 68, 68);">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                                    </svg>
                                                    <span class="text-sm flex-1" style="color: rgb(239, 68, 68);">
                                                        El archivo será eliminado al guardar
                                                    </span>
                                                    <button wire:click="cancelRemoveDocumentFile" 
                                                            type="button"
                                                            class="px-3 py-1 text-xs rounded-lg transition-all"
                                                            style="color: var(--period-detail-text-primary);"
                                                            onmouseover="this.style.opacity='0.8'"
                                                            onmouseout="this.style.opacity='1'">
                                                        Cancelar
                                                    </button>
                                                </div>
                                            @endif
                                            
                                            <flux:error name="documentFile" />
                                        </flux:field>

                                        {{-- Ejemplo (PDF) --}}
                                        <div class="lg:col-span-2">
                                            <label class="block text-sm font-semibold mb-3 flex items-center gap-2" 
                                                style="color: var(--period-detail-text-primary);">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                                    style="color: var(--period-detail-brand-primary);">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                                </svg>
                                                Ejemplo (PDF)
                                                <span class="text-xs font-normal" style="color: var(--period-detail-text-secondary);">- Archivo de referencia para estudiantes</span>
                                            </label>
                                            
                                            <input type="file" wire:model="documentExample" accept=".pdf" class="hidden" id="documentExample">
                                            
                                            <label for="documentExample"
                                                class="group flex items-center justify-center w-full px-6 py-6 border-2 border-dashed rounded-xl cursor-pointer transition-all"
                                                style="background-color: var(--period-detail-card-bg); 
                                                    border-color: var(--period-detail-border);"
                                                onmouseover="this.style.borderColor='var(--period-detail-brand-secondary)'; this.style.backgroundColor='var(--period-detail-card-bg)';"
                                                onmouseout="this.style.borderColor='var(--period-detail-border)'; this.style.backgroundColor='var(--period-detail-card-bg)';">
                                                <div class="text-center">
                                                    <svg class="w-10 h-10 mx-auto mb-2 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                                        style="color: var(--period-detail-text-secondary);">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                                    </svg>
                                                    <p class="text-sm font-medium transition-colors" 
                                                    style="color: var(--period-detail-text-secondary);">
                                                        Subir ejemplo
                                                    </p>
                                                </div>
                                            </label>

                                            @if ($documentExample)
                                                <div class="mt-3 p-3 rounded-lg flex items-center gap-3" 
                                                    style="background-color: var(--period-detail-card-bg); 
                                                            border: 1px solid var(--period-detail-border);">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                                        style="color: var(--period-detail-text-secondary);">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                                    </svg>
                                                    <span class="text-sm flex-1" style="color: var(--period-detail-text-secondary);">{{ $documentExample->getClientOriginalName() }}</span>
                                                    <button wire:click="$set('documentExample', null)" 
                                                            type="button"
                                                            style="color: var(--period-detail-text-secondary);"
                                                            onmouseover="this.style.opacity='0.7'"
                                                            onmouseout="this.style.opacity='1'">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                                        </svg>
                                                    </button>
                                                </div>
                                            @elseif($editingDocumentId && $period->files->find($editingDocumentId)?->example_name_file && !$removeExampleFile)
                                                <div class="mt-3 p-3 rounded-lg flex items-center gap-3" 
                                                    style="background-color: var(--period-detail-card-bg); 
                                                            border: 1px solid var(--period-detail-border);">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                                        style="color: var(--period-detail-text-secondary);">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                                    </svg>
                                                    <span class="text-sm flex-1" style="color: var(--period-detail-text-secondary);">
                                                        Archivo actual: {{ $period->files->find($editingDocumentId)->example_name_file }}
                                                    </span>
                                                    <button wire:click="removeExistingExampleFile" 
                                                            type="button"
                                                            class="p-1 rounded-full transition-all"
                                                            style="color: var(--period-detail-text-secondary);"
                                                            onmouseover="this.style.opacity='0.8'"
                                                            onmouseout="this.style.opacity='1'">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                                        </svg>
                                                    </button>
                                                </div>
                                            @elseif($editingDocumentId && $removeExampleFile)
                                                <div class="mt-3 p-3 rounded-lg flex items-center gap-3" 
                                                    style="background-color: rgba(239, 68, 68, 0.1); border: 1px solid rgba(239, 68, 68, 0.3);">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                                        style="color: rgb(239, 68, 68);">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                                    </svg>
                                                    <span class="text-sm flex-1" style="color: rgb(239, 68, 68);">
                                                        El archivo de ejemplo será eliminado al guardar
                                                    </span>
                                                    <button wire:click="cancelRemoveExampleFile" 
                                                            type="button"
                                                            class="px-3 py-1 text-xs rounded-lg transition-all"
                                                            style="color: var(--period-detail-text-primary);"
                                                            onmouseover="this.style.opacity='0.8'"
                                                            onmouseout="this.style.opacity='1'">
                                                        Cancelar
                                                    </button>
                                                </div>
                                            @endif                      
                                        </div>
                                    @endif
                                @endif

                                {{-- ============ FIRMAN ============ --}}
                                <flux:field class="lg:col-span-2">
                                    <flux:label class="flex items-center">
                                        <svg class="w-4 h-4 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                            style="color: var(--period-detail-brand-primary);">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                        <span>Firman</span>
                                    </flux:label>
                                    <flux:textarea wire:model.defer="documentFirman" 
                                        rows="3"
                                        placeholder="Escribe aquí..."
                                        style="background-color: var(--period-detail-card-bg); color: var(--period-detail-text-primary);"
                                        class="w-full px-4 py-3 rounded-xl transition-all resize-none"/>
                                    <flux:error name="documentFirman" />
                                </flux:field>

                                {{-- ============ OBSERVACIONES ============ --}}
                                <flux:field class="lg:col-span-2">
                                    <flux:label class="flex items-center">
                                        <svg class="w-4 h-4 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                            style="color: var(--period-detail-brand-primary);">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                        </svg>
                                        <span>Observaciones</span>
                                    </flux:label>
                                    <flux:textarea wire:model.defer="documentObservations" 
                                        rows="4"
                                        placeholder="Escribe aquí..."
                                        style="background-color: var(--period-detail-card-bg); color: var(--period-detail-text-primary);"
                                        class="w-full px-4 py-3 rounded-xl transition-all resize-none"/>
                                    <flux:error name="documentObservations" />
                                </flux:field>

                            </div>

                            {{-- Action Buttons --}}
                            <div class="flex justify-end gap-3 mt-8 pt-6" style="border-top: 1px solid var(--period-detail-border);">
                                @if($editingDocumentId)
                                    <button wire:click="cancelEditDocument"
                                            class="px-6 py-3 font-medium rounded-xl transition-colors"
                                            style="
                                    background-color: var(--period-detail-btn-cancel-bg);
                                        color: var(--period-detail-btn-cancel-text);
                                "
                                onmouseover="this.style.transform='translateY(-2px)'; this.style.filter='brightness(1.1)';"
                                    onmouseout="this.style.transform='translateY(0)'; this.style.filter='brightness(1)';">
                                        Cancelar
                                    </button>
                                @endif
                                
                                <button wire:click="{{ $editingDocumentId ? 'saveDocument' : 'createDocument' }}"
                                        wire:loading.attr="disabled"
                                        class="px-6 py-3 font-semibold rounded-xl shadow-sm transition-all disabled:opacity-50 disabled:cursor-not-allowed flex items-center gap-2"
                                        style="background: linear-gradient(135deg, var(--period-detail-brand-primary) 0%, var(--period-detail-brand-secondary) 100%); 
                                            color: var(--period-detail-text-icon);"
                                        onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 20px 25px -5px rgba(0, 0, 0, 0.3)';"
                                        onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 10px 15px -3px rgba(0, 0, 0, 0.3)';">
                                    <span wire:loading.remove wire:target="{{ $editingDocumentId ? 'saveDocument' : 'createDocument' }}">
                                        {{ $editingDocumentId ? 'Guardar Cambios' : 'Crear Documento' }}
                                    </span>
                                    <span wire:loading wire:target="{{ $editingDocumentId ? 'saveDocument' : 'createDocument' }}" class="flex items-center gap-2">
                                        <svg class="animate-spin h-5 w-5" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                        Procesando...
                                    </span>
                                </button>
                            </div>
                        </div>
                </div>

                {{-- ================== LISTA DE DOCUMENTOS ================== --}}
                @if(!$editingDocumentId)
                    <div class="backdrop-blur-xl rounded-2xl shadow-2xl overflow-hidden" 
                         style="background-color: var(--period-detail-bg); ">
                        
                        <div class="p-6" 
                            style="background: linear-gradient(135deg, var(--period-detail-card-header-bg) 0%, var(--period-detail-card-header-bg-2) 100%);
                                    border-bottom: 1px solid var(--period-detail-border);">
                            <div class="flex items-center justify-between gap-4">
                                {{-- Título --}}
                                <div>
                                    <h2 class="text-xl font-bold flex items-center gap-2" style="color: var(--period-detail-text-primary);">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                            style="color: var(--period-detail-brand-primary);">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                        </svg>
                                        Documentos Base Creados
                                    </h2>
                                    <p class="text-sm mt-1" style="color: var(--period-detail-text-secondary);">
                                        {{ $period->files->count() }} {{ $period->files->count() === 1 ? 'documento disponible' : 'documentos disponibles' }}
                                    </p>
                                </div>

                                {{-- Buscador a la derecha --}}
                                <div class="flex-1 max-w-xs ml-auto">
                                    <div class="relative w-full">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                                style="color: var(--period-detail-text-primary);">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                            </svg>
                                        </div>
                                        <input
                                            type="text"
                                            wire:model.live.debounce.300ms="searchDocuments"
                                            placeholder="Buscar por nombre del documento..."
                                            class="w-full pl-10 pr-4 py-2.5 rounded-lg text-sm transition-all focus:ring-2 focus:outline-none"
                                            style="
                                                background-color: var(--period-detail-card-bg);
                                                color: var(--period-detail-text-primary);
                                                border: 1px solid var(--period-detail-border);
                                            "
                                        >
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div>
                            @forelse($paginatedFiles as $file)
                                <div class="p-6 transition-all group" 
                                     onmouseover="this.style.backgroundColor='var(--period-detail-card-bg)'"
                                     onmouseout="this.style.backgroundColor='transparent'">
                                    <div class="flex items-start justify-between gap-4">
                                        <div class="flex-1 min-w-0">
                                            <div class="flex items-start gap-3">
                                                <div class="w-10 h-10 rounded-lg flex items-center justify-center flex-shrink-0" 
                                                    style="background-color: var(--period-detail-status-normal-bg);">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                                        style="color: var(--period-detail-status-normal-text);">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                                    </svg>
                                                </div>
                                                <div class="flex-1 min-w-0">
                                                    <h3 class="text-lg font-semibold truncate" style="color: var(--period-detail-text-primary);">{{ $file->name }}</h3>
                                                    <div class="flex flex-wrap items-center gap-3 mt-2 text-sm" style="color: var(--period-detail-text-secondary);">
                                                        <!-- Fecha de subida -->
                                                        <span class="flex items-center gap-1">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                            </svg>
                                                            Subido: {{ $file->created_at->format('d/m/Y') }}
                                                        </span>

                                                        <!-- Fecha límite con mismo formato -->
                                                        @if($file->limit_date)
                                                            <span class="flex items-center gap-1">
                                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                                </svg>
                                                                Límite: {{ \Carbon\Carbon::parse($file->limit_date)->format('d/m/Y') }}
                                                            </span>
                                                        @endif
                                                    </div>

                                                </div>
                                            </div>
                                        </div>

                                        <div class="flex gap-1 items-center">
                                            {{-- Botón Word --}}
                                            @if($file->file_path)
                                                <div class="relative group/word">
                                                    <button wire:click="previewFile('{{ $file->file_path }}','{{ $file->name_file }}')"
                                                            class="w-8 h-8 flex items-center justify-center rounded-lg transition-all duration-200 cursor-pointer text-xs hover:-translate-y-0.5"
                                                            style="background-color: var(--period-detail-btn-word-bg); color: var(--period-detail-btn-word-text);"
                                                            onmouseover="this.style.backgroundColor='var(--period-detail-btn-word-hover)'"
                                                            onmouseout="this.style.backgroundColor='var(--period-detail-btn-word-bg)'">
                                                        <i class="fas fa-file-word"></i>
                                                    </button>
                                                    <div class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 px-2 py-1 text-xs font-medium text-white rounded-lg opacity-0 group-hover/word:opacity-100 transition-all duration-200 pointer-events-none whitespace-nowrap z-50"
                                                        style="background-color: var(--period-detail-btn-word-bg); color: var(--period-detail-btn-word-text); border: 1px solid var(--period-detail-btn-word-hover);">
                                                        Ver Word
                                                        <div class="absolute top-full left-1/2 -translate-x-1/2 border-4 border-transparent"
                                                            style="border-top-color: var(--period-detail-btn-word-bg);"></div>
                                                    </div>
                                                </div>
                                            @endif

                                            {{-- Botón PDF --}}
                                            @if($file->example_path)
                                                <div class="relative group/pdf">
                                                    <button wire:click="previewFile('{{ $file->example_path }}','{{ $file->example_name_file }}')"
                                                            class="w-8 h-8 flex items-center justify-center rounded-lg transition-all duration-200 cursor-pointer text-xs hover:-translate-y-0.5"
                                                            style="background-color: var(--period-detail-btn-pdf-bg); color: var(--period-detail-btn-pdf-text);"
                                                            onmouseover="this.style.backgroundColor='var(--period-detail-btn-pdf-hover)'"
                                                            onmouseout="this.style.backgroundColor='var(--period-detail-btn-pdf-bg)'">
                                                        <i class="fas fa-file-pdf"></i>
                                                    </button>
                                                    <div class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 px-2 py-1 text-xs font-medium rounded-lg opacity-0 group-hover/pdf:opacity-100 transition-all duration-200 pointer-events-none whitespace-nowrap z-50"
                                                        style="background-color: var(--period-detail-btn-pdf-bg); color: var(--period-detail-btn-pdf-text); border: 1px solid var(--period-detail-btn-pdf-hover);">
                                                        Ver PDF
                                                        <div class="absolute top-full left-1/2 -translate-x-1/2 border-4 border-transparent"
                                                            style="border-top-color: var(--period-detail-btn-pdf-bg);"></div>
                                                    </div>
                                                </div>
                                            @endif

                                            {{-- Botón Editar --}}
                                            <div class="relative group/edit">
                                                <button wire:click="editDocument({{ $file->id }})"
                                                        class="w-8 h-8 flex items-center justify-center rounded-lg transition-all duration-200 cursor-pointer text-xs hover:-translate-y-0.5"
                                                        style="background-color: var(--period-detail-btn-edit-bg); color: var(--period-detail-btn-edit-text);"
                                                        onmouseover="this.style.backgroundColor='var(--period-detail-btn-edit-hover)'"
                                                        onmouseout="this.style.backgroundColor='var(--period-detail-btn-edit-bg)'">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <div class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 px-2 py-1 text-xs font-medium rounded-lg opacity-0 group-hover/edit:opacity-100 transition-all duration-200 pointer-events-none whitespace-nowrap z-50"
                                                    style="background-color: var(--period-detail-btn-edit-bg); color: var(--period-detail-btn-edit-text); border: 1px solid var(--period-detail-btn-edit-hover);">
                                                    Editar
                                                    <div class="absolute top-full left-1/2 -translate-x-1/2 border-4 border-transparent"
                                                        style="border-top-color: var(--period-detail-btn-edit-bg);"></div>
                                                </div>
                                            </div>

                                            {{-- Botón Eliminar --}}
                                            <div class="relative group/delete">
                                                <button wire:click="deleteDocument({{ $file->id }})"
                                                        class="w-8 h-8 flex items-center justify-center rounded-lg transition-all duration-200 cursor-pointer text-xs hover:-translate-y-0.5"
                                                        style="background-color: var(--period-detail-btn-delete-bg); color: var(--period-detail-btn-delete-text);"
                                                        onmouseover="this.style.backgroundColor='var(--period-detail-btn-delete-hover)'"
                                                        onmouseout="this.style.backgroundColor='var(--period-detail-btn-delete-bg)'">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                                <div class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 px-2 py-1 text-xs font-medium rounded-lg opacity-0 group-hover/delete:opacity-100 transition-all duration-200 pointer-events-none whitespace-nowrap z-50"
                                                    style="background-color: var(--period-detail-btn-delete-bg); color: var(--period-detail-btn-delete-text); border: 1px solid var(--period-detail-btn-delete-hover);">
                                                    Eliminar
                                                    <div class="absolute top-full left-1/2 -translate-x-1/2 border-4 border-transparent"
                                                        style="border-top-color: var(--period-detail-btn-delete-bg);"></div>
                                                </div>
                                            </div>

                                        </div>



                                    </div>
                                </div>
                            @empty
                                <div class="p-16 text-center">
                                    <div class="w-20 h-20 mx-auto mb-4 rounded-2xl flex items-center justify-center" 
                                         style="background-color: var(--period-detail-content-bg);">
                                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                             style="color: var(--period-detail-text-secondary);">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                        </svg>
                                    </div>
                                    <p class="text-lg font-semibold mb-2" style="color: var(--period-detail-text-secondary);">No hay documentos base</p>
                                    <p class="text-sm" style="color: var(--period-detail-text-secondary);">Crea documentos para que los estudiantes puedan subirlos</p>
                                </div>
                            @endforelse
                        </div>

                        <div class="p-4">
                            {{ $paginatedFiles->links() }}
                        </div>
                    </div>
                @endif

            </div>
        @endif

        {{-- REVISIÓN DE DOCUMENTOS - CON DISEÑO DE GESTIÓN DE ESTUDIANTES --}}
        @if($activeTab === 'revision')
            <div class="backdrop-blur-xl rounded-2xl shadow-2xl overflow-hidden" 
                 style="background-color: var(--period-detail-bg); 
                        border: 1px solid var(--period-detail-border);">

                {{-- Header --}}
                <div class="p-6" style="border-bottom: 1px solid var(--period-detail-border);">
                    <h2 class="text-2xl font-bold mb-2" style="color: var(--period-detail-text-primary);">
                        Revisión de Documentos
                    </h2>
                    <p style="color: var(--period-detail-text-secondary);">
                        Revisa los documentos entregados por los estudiantes
                    </p>

                    {{-- Controles de búsqueda y filtros --}}
                    <div class="mt-4 flex flex-col lg:flex-row gap-3 items-center">

                        {{-- 🔍 Búsqueda --}}
                        <div class="flex-1 min-w-0">
                            <div class="relative w-full">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                        style="color: var(--period-detail-text-primary);">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                    </svg>
                                </div>
                                <input
                                    type="text"
                                    wire:model.live.debounce.300ms="searchRevision"
                                    placeholder="Buscar por nombre o número de control..."
                                    class="w-full pl-10 pr-4 py-2.5 rounded-lg text-sm transition-all focus:ring-2 focus:outline-none"
                                    style="
                                        background-color: var(--period-detail-card-bg);
                                        color: var(--period-detail-text-primary);
                                        border: 1px solid var(--period-detail-border);
                                    "
                                >
                            </div>
                        </div>

                        {{-- 📚 Filtro de carrera --}}
                        <flux:select
                            wire:model.live="careerFilter"
                            class="w-36 lg:w-48 px-3 py-2.5 rounded-lg text-sm font-medium transition-all"
                            style="
                                background-color: var(--period-detail-card-bg);
                                color: var(--period-detail-text-primary);
                                border: 1px solid var(--period-detail-border);
                            "
                        >
                            <option value="">Todas las carreras</option>
                            @foreach($careers as $career)
                                <option value="{{ $career->id }}">{{ $career->name }}</option>
                            @endforeach
                        </flux:select>

                        {{-- 📋 Filtro de estado --}}
                        <flux:select
                            wire:model.live="statusFilter"
                            class="w-36 lg:w-48 px-3 py-2.5 rounded-lg text-sm font-medium transition-all"
                            style="
                                background-color: var(--period-detail-card-bg);
                                color: var(--period-detail-text-primary);
                                border: 1px solid var(--period-detail-border);
                            "
                        >
                            <option value="">Todos</option>
                            <option value="pending">Pendientes</option>
                            <option value="approved">Aprobados</option>
                            <option value="rejected">Rechazados</option>
                        </flux:select>

                        {{-- 💾 Exportar Excel --}}
                        <div class="relative group/excel">
                            <button 
                                wire:click="exportExcel"
                                wire:loading.attr="disabled"
                                class="px-5 py-2.5 font-semibold rounded-lg shadow-sm transition-all disabled:opacity-50 disabled:cursor-not-allowed flex items-center gap-2 whitespace-nowrap"
                                style="background: var(--period-detail-bg); color: var(--period-detail-btn-text);"
                                onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 20px 25px -5px rgba(0, 0, 0, 0.3)';"
                                onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 10px 15px -3px rgba(0, 0, 0, 0.3)';"
                            >
                                <span wire:loading.remove wire:target="exportExcel" class="flex items-center gap-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                </span>
                                <span wire:loading wire:target="exportExcel" class="flex items-center gap-2">
                                    <svg class="animate-spin h-5 w-5" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    Procesando...
                                </span>
                            </button>
                            <div class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 px-2 py-1 text-xs font-medium rounded-lg opacity-0 group-hover/excel:opacity-100 transition-all duration-200 pointer-events-none whitespace-nowrap z-50"
                                style="background: linear-gradient(135deg, var(--period-detail-brand-primary) 0%, var(--period-detail-brand-secondary) 100%); color: var(--period-detail-text-icon);">
                                Exportar Excel
                                <div class="absolute top-full left-1/2 -translate-x-1/2 border-4 border-transparent"
                                    style="border-top-color: var(--period-detail-brand-secondary);"></div>
                            </div>
                        </div>


                    </div>

                </div>

                {{-- ================== TABLA MEJORADA CON VISTA EXPANDIBLE ================== --}}
                <div class="backdrop-blur-xl rounded-2xl shadow-2xl overflow-hidden" 
                    style="background-color: var(--period-detail-bg); ">
                    
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead style="background: linear-gradient(135deg, var(--period-detail-card-header-approved-bg) 0%, var(--period-detail-card-header-approved-bg-2) 100%);">
                                <tr>
                                    <th class="px-6 py-4 text-left text-xs font-semibold uppercase w-12" 
                                        style="color: var(--period-detail-text-secondary);"></th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold uppercase" 
                                        style="color: var(--period-detail-text-secondary);">Estudiante</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold uppercase" 
                                        style="color: var(--period-detail-text-secondary);">Carrera</th>
                                    <th class="px-6 py-4 text-center text-xs font-semibold uppercase" 
                                        style="color: var(--period-detail-text-secondary);">
                                        <div class="flex items-center justify-center gap-2">
                                            Aprobados
                                        </div>
                                    </th>
                                    <th class="px-6 py-4 text-center text-xs font-semibold uppercase" 
                                        style="color: var(--period-detail-text-secondary);">
                                        <div class="flex items-center justify-center gap-2">
                                            Pendientes
                                        </div>
                                    </th>
                                    <th class="px-6 py-4 text-center text-xs font-semibold uppercase" 
                                        style="color: var(--period-detail-text-secondary);">
                                        <div class="flex items-center justify-center gap-2">
                                            Rechazados
                                        </div>
                                    </th>
                                    <th class="px-6 py-4 text-center text-xs font-semibold uppercase" 
                                        style="color: var(--period-detail-text-secondary);">Seguimiento</th>
                                </tr>
                            </thead>

                            <tbody style="border-top: 1px solid var(--period-detail-border);">
                                @forelse($studentsRevision as $student)
                                    {{-- Fila principal del estudiante --}}
                                    <tr 
                                        class="transition hover-row cursor-pointer"
                                        wire:click="toggleStudentExpand({{ $student->id }})"
                                        onmouseover="this.style.backgroundColor='var(--period-detail-card-bg)'" 
                                        onmouseout="this.style.backgroundColor='transparent'"
                                        style="border-bottom: 1px solid var(--period-detail-border);"
                                    >
                                        {{-- Botón expandir (solo visual, ya no necesita click) --}}
                                        <td class="px-6 py-4">
                                            <div class="p-1.5 rounded-lg transition" style="background-color: var(--period-detail-card-bg);">
                                                <svg class="w-5 h-5 transition-transform {{ $expandedStudent === $student->id ? 'rotate-90' : '' }}" 
                                                    fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                                    style="color: var(--period-detail-text-secondary);">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                                </svg>
                                            </div>
                                        </td>

                                        {{-- Estudiante --}}
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-3">
                                                <div>
                                                    <p class="font-medium" style="color: var(--period-detail-text-primary);">
                                                        {{ $student->name }} {{ $student->last_name_paterno }} {{ $student->last_name_materno }}
                                                    </p>
                                                    <p class="text-xs" style="color: var(--period-detail-text-secondary);">{{ $student->control_number }}</p>
                                                    <p class="text-xs" style="color: var(--period-detail-text-secondary);">{{ $student->personal_email }}</p>
                                                </div>
                                            </div>
                                        </td>

                                        {{-- Carrera --}}
                                        <td class="px-6 py-4">
                                            <span class="text-sm" style="color: var(--period-detail-text-primary);">{{ Str::limit($student->career->name ?? 'N/A', 30) }}</span>
                                        </td>

                                        {{-- Aprobados --}}
                                        <td class="px-6 py-4 text-center">
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-sm font-semibold" 
                                                style="background-color: rgba(16, 185, 129, 0.2); color: var(--period-detail-status-approved-icon-color);">
                                                {{ $student->approved_count ?? 0 }}
                                            </span>
                                        </td>

                                        {{-- Pendientes --}}
                                        <td class="px-6 py-4 text-center">
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-sm font-semibold" 
                                                style="background-color: rgba(232, 210, 50, 0.2); color: var(--period-detail-status-pending);">
                                                {{ $student->pending_count ?? 0 }}
                                            </span>
                                        </td>

                                        {{-- Rechazados --}}
                                        <td class="px-6 py-4 text-center">
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-sm font-semibold" 
                                                style="background-color: rgba(239, 68, 68, 0.2); color: var(--period-detail-status-rejected-text);">
                                                {{ $student->rejected_count ?? 0 }}
                                            </span>
                                        </td>

                                        {{-- Acciones (evitar que disparen expand) --}}
                                        <td class="px-6 py-4">
                                            <div class="flex items-center justify-center gap-2" onclick="event.stopPropagation()">
                                                <div class="relative group/pdf-seguimiento">
                                                    <button
                                                        wire:click="exportStudentPDF({{ $student->id }})"
                                                        class="w-8 h-8 flex items-center justify-center rounded-lg transition-all duration-200 cursor-pointer text-xs hover:-translate-y-0.5"
                                                        style="background-color: var(--period-detail-btn-bg-g); color: var(--period-detail-btn-text-g);"
                                                        onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 5px 10px rgba(0,0,0,0.2)'; this.style.backgroundColor='var(--period-detail-btn-hover-g)'; this.style.color='var(--period-detail-btn-text-g)';"
                                                        onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none'; this.style.backgroundColor='var(--period-detail-btn-bg-g)'; this.style.color='var(--period-detail-btn-text-g)';"
                                                    >
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                                        </svg>
                                                    </button>
                                                    <div class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 px-2 py-1 text-xs font-medium rounded-lg opacity-0 group-hover/pdf-seguimiento:opacity-100 transition-all duration-200 pointer-events-none whitespace-nowrap z-50"
                                                        style="background-color: var(--period-detail-btn-bg-g); color: var(--period-detail-btn-text-g); border: 1px solid var(--period-detail-border);">
                                                        Descargar
                                                        <div class="absolute top-full left-1/2 -translate-x-1/2 border-4 border-transparent"
                                                            style="border-top-color: var(--period-detail-btn-hover-g);"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>


                                    {{-- Fila expandible con documentos --}}
                                    @if($expandedStudent === $student->id)
                                        <tr style="background-color: var(--period-detail-card-bg);">
                                            <td colspan="8" class="px-6 py-6">
                                                <div class="space-y-4">
                                                    <h4 class="text-sm font-semibold flex items-center gap-2 mb-4" 
                                                        style="color: var(--period-detail-text-primary);">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                                            style="color: var(--period-detail-brand-primary);">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                                        </svg>
                                                        Documentos del estudiante
                                                    </h4>

                                                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-3">
                                                        @forelse($this->getStudentDocuments($student->id) as $doc)
                                                            <div class="group rounded-lg p-4 transition cursor-pointer"
                                                                wire:click="quickReviewDocument({{ $doc->id }})"
                                                                style="background-color: var(--period-detail-bg); 
                                                                    border: 1px solid var(--period-detail-border);"
                                                                onmouseover="this.style.borderColor='var(--period-detail-brand-primary)'"
                                                                onmouseout="this.style.borderColor='var(--period-detail-border)'">

                                                                <div class="flex items-start justify-between gap-3">
                                                                    <div class="flex-1 min-w-0">
                                                                        <div class="flex items-center gap-2 mb-2">
                                                                            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                                                                style="color: var(--period-detail-brand-primary);">
                                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                                                            </svg>
                                                                            <h5 class="text-sm font-semibold truncate" 
                                                                                style="color: var(--period-detail-text-primary);">
                                                                                {{ $doc->file->name ?? $doc->name }}
                                                                            </h5>
                                                                        </div>

                                                                        {{-- Mostrar nombre del archivo según el upload_mode --}}
                                                                        @php
                                                                            $hasFile = false;
                                                                            $fileName = '';

                                                                            if ($doc->file->upload_mode === 'admin_only') {

                                                                                if ($doc->file->is_individual) {
                                                                                    // admin_only + individual → depende del archivo individual
                                                                                    $individualUpload = \App\Models\FileStudentUpload::where('file_id', $doc->file->id)
                                                                                        ->where('student_id', $student->id)
                                                                                        ->first();

                                                                                    if ($individualUpload) {
                                                                                        $hasFile = true;
                                                                                        $fileName = Str::before(
                                                                                            pathinfo($individualUpload->name_file, PATHINFO_FILENAME),
                                                                                            '_'
                                                                                        );
                                                                                    }
                                                                                }

                                                                                // admin_only + NO individual → no mostrar nada
                                                                            } else {
                                                                                // user_only o bidirectional
                                                                                if ($doc->student_file_path) {
                                                                                    $hasFile = true;
                                                                                    $fileName = Str::before(
                                                                                        pathinfo($doc->student_file_name, PATHINFO_FILENAME),
                                                                                        '_'
                                                                                    );
                                                                                }
                                                                            }
                                                                        @endphp

                                                                        @if($hasFile)
                                                                            <p class="text-xs truncate mb-2"
                                                                                style="color: var(--period-detail-text-secondary);">
                                                                                {{ $fileName }}
                                                                            </p>

                                                                        @elseif($doc->file->upload_mode !== 'admin_only')
                                                                            <p class="text-xs mb-2"
                                                                                style="color: var(--period-detail-text-secondary); opacity: 0.6;">
                                                                                Sin entregar
                                                                            </p>
                                                                        @endif

                                                                    </div>

                                                                    <div class="flex-shrink-0">
                                                                        @if($doc->file->upload_mode === 'admin_only')
                                                                            {{-- Para admin_only --}}
                                                                            @if($doc->file->is_individual)
                                                                                @php
                                                                                    $individualUpload = \App\Models\FileStudentUpload::where('file_id', $doc->file->id)
                                                                                        ->where('student_id', $student->id)
                                                                                        ->first();
                                                                                @endphp
                                                                                @if($individualUpload)
                                                                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-semibold" 
                                                                                        style="background-color: rgba(16, 185, 129, 0.2); color: var(--period-detail-status-approved-icon-color);">
                                                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                                                                        </svg>
                                                                                        Subido
                                                                                    </span>
                                                                                @else
                                                                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-semibold" 
                                                                                        style="background-color: var(--period-detail-card-bg); color: var(--period-detail-text-secondary); border: 1px solid var(--period-detail-border); opacity: 0.6;">
                                                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/>
                                                                                        </svg>
                                                                                        Sin Subir
                                                                                    </span>
                                                                                @endif
                                                                            @else
                                                                                {{-- Documento base --}}
                                                                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-semibold" 
                                                                                    style="background-color: rgba(16, 185, 129, 0.2); color: var(--period-detail-status-approved-icon-color);">
                                                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                                                                    </svg>
                                                                                    Base
                                                                                </span>
                                                                            @endif
                                                                        @else
                                                                            {{-- Para user_only o bidirectional --}}
                                                                            @if($doc->student_file_path)
                                                                                @if($doc->status === 'revisado')
                                                                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-semibold" 
                                                                                        style="background-color: rgba(16, 185, 129, 0.2); color: var(--period-detail-status-approved-icon-color);">
                                                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                                                                        </svg>
                                                                                        Aprobado
                                                                                    </span>
                                                                                @elseif($doc->status === 'rechazado')
                                                                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-semibold" 
                                                                                        style="background-color: rgba(239, 68, 68, 0.2); color: var(--period-detail-status-rejected-text);">
                                                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                                                                        </svg>
                                                                                        Rechazado
                                                                                    </span>
                                                                                @else
                                                                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-semibold" 
                                                                                        style="background-color: rgba(232, 210, 50, 0.2); color: var(--period-detail-status-pending);">
                                                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                                                        </svg>
                                                                                        Revisar
                                                                                    </span>
                                                                                @endif
                                                                            @else
                                                                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-semibold" 
                                                                                    style="background-color: var(--period-detail-card-bg); color: var(--period-detail-text-secondary); border: 1px solid var(--period-detail-border); opacity: 0.6;">
                                                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/>
                                                                                    </svg>
                                                                                    Pendiente
                                                                                </span>
                                                                            @endif
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @empty
                                                            <div class="col-span-2 text-center py-8" 
                                                                style="color: var(--period-detail-text-secondary);">
                                                                <svg class="w-12 h-12 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                                                </svg>
                                                                <p class="text-sm">No hay documentos asignados</p>
                                                            </div>
                                                        @endforelse
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endif
                                @empty
                                    <tr>
                                        <td colspan="8" class="px-6 py-16 text-center">
                                            <div class="flex flex-col items-center">
                                                <svg class="w-16 h-16 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                                    style="color: var(--period-detail-text-secondary); opacity: 0.4;">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                                        </svg>
                                        <p class="font-semibold mb-1" style="color: var(--period-detail-text-secondary);">No se encontraron estudiantes</p>
                                        <p class="text-sm" style="color: var(--period-detail-text-secondary); opacity: 0.7;">Intenta con otros filtros de búsqueda</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Paginación --}}
            @if($studentsRevision->hasPages())
                <div class="p-4" style="border-top: 1px solid var(--period-detail-border);">
                    {{ $studentsRevision->links() }}
                </div>
                        @endif
                    </div>

                </div>
            @endif
    </div>




    
    {{-- ================== MODAL DE VISTA PREVIA GESTIÓN DE ESTUDIANTES================== --}}
    @if($showModal && $selectedStudent)
        <flux:modal 
            wire:model="showModal" 
            :dismissible="false"
            class="w-[95vw] sm:w-[90vw] md:w-[85vw] lg:w-[1200px] xl:w-[1400px] max-w-[95vw]">
            <div class="flex flex-col h-[90vh] max-h-[95vh]">

                {{-- Header Elegante --}}
                <div 
                    class="relative px-6 py-5 flex items-center justify-between overflow-hidden flex-shrink-0 rounded-t-xl"
                    style="
                        background: linear-gradient(
                            135deg,
                            var(--period-detail-card-header-bg) 0%,
                            var(--period-detail-card-header-bg-2) 100%
                        );
                        border-bottom: 1px solid var(--period-detail-border);
                    ">
                    
                    <div class="relative flex items-center gap-4 flex-1 min-w-0">
                        {{-- Avatar con iniciales --}}
                        <div 
                            class="w-14 h-14 rounded-xl flex items-center justify-center text-white font-bold text-lg shadow-lg flex-shrink-0"
                            style="
                                background: linear-gradient(135deg, var(--period-detail-brand-primary), var(--period-detail-brand-secondary));
                            ">
                            {{ strtoupper(substr($selectedStudent->name, 0, 1)) }}{{ strtoupper(substr($selectedStudent->last_name_paterno, 0, 1)) }}
                        </div>

                        {{-- Info del estudiante --}}
                        <div class="flex-1 min-w-0">
                            <h2 class="text-xl font-bold truncate" style="color: var(--period-detail-text-primary);">
                                {{ $selectedStudent->name }}
                                {{ $selectedStudent->last_name_paterno }}
                                {{ $selectedStudent->last_name_materno }}
                            </h2>
                            <div class="flex items-center gap-3 mt-1">
                                <span class="text-sm font-medium" style="color: var(--period-detail-text-secondary);">
                                    {{ $selectedStudent->control_number }} - {{ $selectedStudent->career?->name ?? 'Sin carrera' }}
                                </span>
                            </div>
                        </div>

                        {{-- Badge de estado --}}
                        @if(!$editMode)
                            <div class="flex-shrink-0">
                                <span class="inline-flex items-center px-3 py-1 text-xs font-semibold rounded-full" style="{{ $student->status_style }}">
                                    {{ $student->status_label }}
                                </span>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Contenido Principal con Grid --}}
                <div class="flex-1 overflow-y-auto p-6" style="background-color: var(--period-detail-card-bg);">
                    <div class="max-w-6xl mx-auto">
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">

                            {{-- Tarjeta: Información Personal --}}
                            <div 
                                class="rounded-xl  transition-all duration-300 hover:shadow-lg"
                                style="
                                    background-color: var(--period-detail-bg);
                                ">
                                {{-- Header de la tarjeta --}}
                                <div class="px-5 py-4">
                                    <div class="flex items-center gap-2">
                                        <div class="p-2 rounded-lg" style="background-color: var(--period-detail-card-bg);">
                                            <svg class="w-5 h-5" style="color: var(--period-detail-accent);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                            </svg>
                                        </div>
                                        <h3 class="text-base font-semibold" style="color: var(--period-detail-text-primary);">
                                            Información Personal
                                        </h3>
                                    </div>
                                </div>

                                {{-- Contenido de la tarjeta --}}
                                <div class="p-5 space-y-4">
                                    @if($editMode)
                                        {{-- Modo Edición --}}
                                        <div class="grid grid-cols-1 gap-4">
                                            <div>
                                                <label class="block text-xs font-semibold mb-1.5" style="color: var(--period-detail-text-secondary);">
                                                    Nombre(s)
                                                </label>
                                                <input 
                                                    type="text"
                                                    class="w-full px-3 py-2.5 rounded-lg border text-sm transition-all focus:ring-2 focus:ring-opacity-50"
                                                    style="
                                                        background-color: var(--period-detail-card-bg);
                                                        border-color: var(--period-detail-border);
                                                        color: var(--period-detail-text-primary);
                                                    "
                                                    wire:model.defer="studentData.name">
                                            </div>

                                            <div class="grid grid-cols-2 gap-3">
                                                <div>
                                                    <label class="block text-xs font-semibold mb-1.5" style="color: var(--period-detail-text-secondary);">
                                                        Apellido Paterno
                                                    </label>
                                                    <input 
                                                        type="text"
                                                        class="w-full px-3 py-2.5 rounded-lg border text-sm transition-all"
                                                        style="
                                                            background-color: var(--period-detail-card-bg);
                                                            border-color: var(--period-detail-border);
                                                            color: var(--period-detail-text-primary);
                                                        "
                                                        wire:model.defer="studentData.last_name_paterno">
                                                </div>

                                                <div>
                                                    <label class="block text-xs font-semibold mb-1.5" style="color: var(--period-detail-text-secondary);">
                                                        Apellido Materno
                                                    </label>
                                                    <input 
                                                        type="text"
                                                        class="w-full px-3 py-2.5 rounded-lg border text-sm transition-all"
                                                        style="
                                                            background-color: var(--period-detail-card-bg);
                                                            border-color: var(--period-detail-border);
                                                            color: var(--period-detail-text-primary);
                                                        "
                                                        wire:model.defer="studentData.last_name_materno">
                                                </div>
                                            </div>

                                            <div class="grid grid-cols-2 gap-3">
                                                <div>
                                                    <label class="block text-xs font-semibold mb-1.5" style="color: var(--period-detail-text-secondary);">
                                                        CURP
                                                    </label>
                                                    <input 
                                                        type="text"
                                                        class="w-full px-3 py-2.5 rounded-lg border text-sm transition-all"
                                                        style="
                                                            background-color: var(--period-detail-card-bg);
                                                            border-color: var(--period-detail-border);
                                                            color: var(--period-detail-text-primary);
                                                        "
                                                        wire:model.defer="studentData.curp">
                                                </div>

                                                <div>
                                                    <label class="block text-xs font-semibold mb-1.5" style="color: var(--period-detail-text-secondary);">
                                                        RFC
                                                    </label>
                                                    <input 
                                                        type="text"
                                                        class="w-full px-3 py-2.5 rounded-lg border text-sm transition-all"
                                                        style="
                                                            background-color: var(--period-detail-card-bg);
                                                            border-color: var(--period-detail-border);
                                                            color: var(--period-detail-text-primary);
                                                        "
                                                        wire:model.defer="studentData.rfc">
                                                </div>
                                            </div>

                                            <div>
                                                <label class="block text-xs font-semibold mb-1.5" style="color: var(--period-detail-text-secondary);">
                                                    Teléfono
                                                </label>
                                                <input 
                                                    type="tel"
                                                    class="w-full px-3 py-2.5 rounded-lg border text-sm transition-all"
                                                    style="
                                                        background-color: var(--period-detail-card-bg);
                                                        border-color: var(--period-detail-border);
                                                        color: var(--period-detail-text-primary);
                                                    "
                                                    wire:model.defer="studentData.phone">
                                            </div>

                                            <div>
                                                <label class="block text-xs font-semibold mb-1.5" style="color: var(--period-detail-text-secondary);">
                                                    Correo Personal
                                                </label>
                                                <input 
                                                    type="email"
                                                    class="w-full px-3 py-2.5 rounded-lg border text-sm transition-all"
                                                    style="
                                                        background-color: var(--period-detail-card-bg);
                                                        border-color: var(--period-detail-border);
                                                        color: var(--period-detail-text-primary);
                                                    "
                                                    wire:model.defer="studentData.personal_email">
                                            </div>
                                        </div>
                                    @else
                                        {{-- Modo Vista --}}
                                        <div class="space-y-3">
                                            <div class="flex items-start justify-between p-3 rounded-lg transition-colors"
                                                style="background-color: var(--period-detail-card-bg);">
                                                <div class="flex-1">
                                                    <p class="text-xs font-medium mb-0.5" style="color: var(--period-detail-text-secondary);">
                                                        CURP
                                                    </p>
                                                    <p class="text-sm font-semibold" style="color: var(--period-detail-text-primary);">
                                                        {{ $selectedStudent->curp ?: '—' }}
                                                    </p>
                                                </div>
                                                <svg class="w-4 h-4 mt-1" style="color: var(--period-detail-text-secondary);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                                </svg>
                                            </div>

                                            <div class="flex items-start justify-between p-3 rounded-lg transition-colors"
                                                style="background-color: var(--period-detail-card-bg);">
                                                <div class="flex-1">
                                                    <p class="text-xs font-medium mb-0.5" style="color: var(--period-detail-text-secondary);">
                                                        RFC
                                                    </p>
                                                    <p class="text-sm font-semibold" style="color: var(--period-detail-text-primary);">
                                                        {{ $selectedStudent->rfc ?: '—' }}
                                                    </p>
                                                </div>
                                                <svg class="w-4 h-4 mt-1" style="color: var(--period-detail-text-secondary);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                                </svg>
                                            </div>

                                            <div class="flex items-start justify-between p-3 rounded-lg transition-colors"
                                                style="background-color: var(--period-detail-card-bg);">
                                                <div class="flex-1">
                                                    <p class="text-xs font-medium mb-0.5" style="color: var(--period-detail-text-secondary);">
                                                        Teléfono
                                                    </p>
                                                    <p class="text-sm font-semibold" style="color: var(--period-detail-text-primary);">
                                                        {{ $selectedStudent->phone ?: '—' }}
                                                    </p>
                                                </div>
                                                <svg class="w-4 h-4 mt-1" style="color: var(--period-detail-text-secondary);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                                </svg>
                                            </div>

                                            <div class="flex items-start justify-between p-3 rounded-lg transition-colors"
                                                style="background-color: var(--period-detail-card-bg);">
                                                <div class="flex-1">
                                                    <p class="text-xs font-medium mb-0.5" style="color: var(--period-detail-text-secondary);">
                                                        Correo Personal
                                                    </p>
                                                    <p class="text-sm font-semibold truncate" style="color: var(--period-detail-text-primary);">
                                                        {{ $selectedStudent->personal_email ?: '—' }}
                                                    </p>
                                                </div>
                                                <svg class="w-4 h-4 mt-1 flex-shrink-0" style="color: var(--period-detail-text-secondary);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                                </svg>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            {{-- Tarjeta: Datos Académicos --}}
                            <div 
                                class="rounded-xl transition-all duration-300 hover:shadow-lg"
                                style="
                                    background-color: var(--period-detail-bg);
                                ">
                                {{-- Header de la tarjeta --}}
                                <div class="px-5 py-4">
                                    <div class="flex items-center gap-2">
                                        <div class="p-2 rounded-lg" style="background-color: var(--period-detail-card-bg);">
                                            <svg class="w-5 h-5" style="color: var(--period-detail-accent);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                            </svg>
                                        </div>
                                        <h3 class="text-base font-semibold" style="color: var(--period-detail-text-primary);">
                                            Datos Académicos
                                        </h3>
                                    </div>
                                </div>

                                {{-- Contenido de la tarjeta --}}
                                <div class="p-5 space-y-4">
                                    @if($editMode)
                                        {{-- Modo Edición --}}
                                        <div class="grid grid-cols-1 gap-4">
                                            <div>
                                                <label class="block text-xs font-semibold mb-1.5" style="color: var(--period-detail-text-secondary);">
                                                    Número de Control
                                                </label>
                                                <input 
                                                    type="text"
                                                    class="w-full px-3 py-2.5 rounded-lg border text-sm transition-all"
                                                    style="
                                                        background-color: var(--period-detail-card-bg);
                                                        border-color: var(--period-detail-border);
                                                        color: var(--period-detail-text-primary);
                                                    "
                                                    wire:model.defer="studentData.control_number">
                                            </div>

                                            <div>
                                                <label class="block text-xs font-semibold mb-1.5" style="color: var(--period-detail-text-secondary);">
                                                    Correo Institucional
                                                </label>
                                                <input 
                                                    type="email"
                                                    class="w-full px-3 py-2.5 rounded-lg border text-sm transition-all"
                                                    style="
                                                        background-color: var(--period-detail-card-bg);
                                                        border-color: var(--period-detail-border);
                                                        color: var(--period-detail-text-primary);
                                                    "
                                                    wire:model.defer="studentData.institutional_email">
                                            </div>

                                            <div class="grid grid-cols-2 gap-3">
                                                {{-- Sistema --}}
                                                <flux:select
                                                    label="Sistema"
                                                    wire:model.defer="studentData.system"
                                                    class="w-full text-sm transition-all"
                                                    style="
                                                        background-color: var(--period-detail-card-bg);
                                                        border-color: var(--period-detail-border);
                                                        color: var(--period-detail-text-primary);
                                                    ">
                                                    <option value="Escolarizado">Escolarizado</option>
                                                    <option value="Sabatino">Sabatino</option>
                                                </flux:select>

                                                {{-- Campus --}}
                                                <flux:select
                                                    label="Campus"
                                                    wire:model.defer="studentData.campus_id"
                                                    class="w-full text-sm transition-all"
                                                    style="
                                                        background-color: var(--period-detail-card-bg);
                                                        border-color: var(--period-detail-border);
                                                        color: var(--period-detail-text-primary);
                                                    ">
                                                    @foreach($campuses as $campus)
                                                        <option value="{{ $campus->id }}">{{ $campus->name }}</option>
                                                    @endforeach
                                                </flux:select>
                                            </div>

                                            <div class="grid grid-cols-2 gap-3 mt-3">
                                                {{-- Semestre --}}
                                                <flux:select
                                                    label="Semestre"
                                                    wire:model.defer="studentData.semester_id"
                                                    class="w-full text-sm transition-all"
                                                    style="
                                                        background-color: var(--period-detail-card-bg);
                                                        border-color: var(--period-detail-border);
                                                        color: var(--period-detail-text-primary);
                                                    ">
                                                    @foreach($semesters as $semester)
                                                        <option value="{{ $semester->id }}">{{ $semester->name }}</option>
                                                    @endforeach
                                                </flux:select>

                                                {{-- Carrera --}}
                                                <flux:select
                                                    label="Carrera"
                                                    wire:model.defer="studentData.career_id"
                                                    class="w-full text-sm transition-all"
                                                    style="
                                                        background-color: var(--period-detail-card-bg);
                                                        border-color: var(--period-detail-border);
                                                        color: var(--period-detail-text-primary);
                                                    ">
                                                    @foreach($careers as $career)
                                                        <option value="{{ $career->id }}">{{ $career->name }}</option>
                                                    @endforeach
                                                </flux:select>
                                            </div>


                                            <div>
                                                <label class="block text-xs font-semibold mb-1.5" style="color: var(--period-detail-text-secondary);">
                                                    Avance Reticular (%)
                                                </label>
                                                <input 
                                                    type="number"
                                                    min="0"
                                                    max="100"
                                                    class="w-full px-3 py-2.5 rounded-lg border text-sm transition-all"
                                                    style="
                                                        background-color: var(--period-detail-card-bg);
                                                        border-color: var(--period-detail-border);
                                                        color: var(--period-detail-text-primary);
                                                    "
                                                    wire:model.defer="studentData.reticular_progress">
                                            </div>
                                        </div>
                                    @else
                                        {{-- Modo Vista --}}
                                        <div class="space-y-3">
                                            <div class="flex items-start justify-between p-3 rounded-lg transition-colors"
                                                style="background-color: var(--period-detail-card-bg);">
                                                <div class="flex-1">
                                                    <p class="text-xs font-medium mb-0.5" style="color: var(--period-detail-text-secondary);">
                                                        Correo Institucional
                                                    </p>
                                                    <p class="text-sm font-semibold truncate" style="color: var(--period-detail-text-primary);">
                                                        {{ $selectedStudent->institutional_email ?: '—' }}
                                                    </p>
                                                </div>
                                                <svg class="w-4 h-4 mt-1 flex-shrink-0" style="color: var(--period-detail-text-secondary);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                                </svg>
                                            </div>

                                            <div class="grid grid-cols-2 gap-3">
                                                <div class="p-3 rounded-lg transition-colors"
                                                    style="background-color: var(--period-detail-card-bg);">
                                                    <p class="text-xs font-medium mb-0.5" style="color: var(--period-detail-text-secondary);">
                                                        Sistema
                                                    </p>
                                                    <p class="text-sm font-semibold" style="color: var(--period-detail-text-primary);">
                                                        {{ $selectedStudent->system ?: '—' }}
                                                    </p>
                                                </div>

                                                <div class="p-3 rounded-lg transition-colors"
                                                    style="background-color: var(--period-detail-card-bg);">
                                                    <p class="text-xs font-medium mb-0.5" style="color: var(--period-detail-text-secondary);">
                                                        Semestre
                                                    </p>
                                                    <p class="text-sm font-semibold" style="color: var(--period-detail-text-primary);">
                                                        {{ $selectedStudent->semester?->name ?: '—' }}
                                                    </p>
                                                </div>
                                            </div>

                                            <div class="flex items-start justify-between p-3 rounded-lg transition-colors"
                                                style="background-color: var(--period-detail-card-bg);">
                                                <div class="flex-1">
                                                    <p class="text-xs font-medium mb-0.5" style="color: var(--period-detail-text-secondary);">
                                                        Campus
                                                    </p>
                                                    <p class="text-sm font-semibold" style="color: var(--period-detail-text-primary);">
                                                        {{ $selectedStudent->campus?->name ?: '—' }}
                                                    </p>
                                                </div>
                                                <svg class="w-4 h-4 mt-1" style="color: var(--period-detail-text-secondary);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                                </svg>
                                            </div>

                                            <div class="flex items-start justify-between p-3 rounded-lg transition-colors"
                                                style="background-color: var(--period-detail-card-bg);">
                                                <div class="flex-1">
                                                    <p class="text-xs font-medium mb-0.5" style="color: var(--period-detail-text-secondary);">
                                                        Periodo
                                                    </p>
                                                    <p class="text-sm font-semibold" style="color: var(--period-detail-text-primary);">
                                                        {{ $selectedStudent->period?->name ?: '—' }}
                                                    </p>
                                                </div>
                                                <svg class="w-4 h-4 mt-1" style="color: var(--period-detail-text-secondary);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                </svg>
                                            </div>

                                            {{-- Avance Reticular con barra de progreso --}}
                                            <div class="flex items-start justify-between p-3 rounded-lg transition-colors"
                                                style="background-color: var(--period-detail-card-bg);">
                                                <div class="flex-1">
                                                    <p class="text-xs font-medium mb-0.5" style="color: var(--period-detail-text-secondary);">
                                                        Avance Reticular
                                                    </p>
                                                    <p class="text-sm font-semibold" style="color: var(--period-detail-text-primary);">
                                                        {{ $selectedStudent->reticular_progress ?? 0 }}%
                                                    </p>
                                                </div>
                                            </div>

                                            
                                        </div>
                                    @endif
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                {{-- Footer con Botones de Acción --}}
                <div 
                    class="px-6 py-4 flex flex-col-reverse sm:flex-row items-center justify-between gap-3 border-t flex-shrink-0 rounded-b-lg"
                    style="
                        background-color: var(--period-detail-card-bg);
                        border-color: var(--period-detail-border);
                    ">
                    {{-- Botones de Navegación y Cerrar --}}
                    <div class="flex gap-2 w-full sm:w-auto">
                        

                        @if($editMode)
                            <button
                                wire:click="cancelEdit"
                                class="flex-1 sm:flex-none px-5 py-2.5 rounded-lg font-medium transition-all duration-200"
                                style="
                                    background-color: var(--period-detail-btn-cancel-bg);
                                    color: var(--period-detail-btn-cancel-text);
                                "
                                onmouseover="this.style.transform='translateY(-2px)'; this.style.filter='brightness(1.05)';"
                                onmouseout="this.style.transform='translateY(0)'; this.style.filter='brightness(1)';">
                                Cancelar
                            </button>
                        @else
                            <button
                            wire:click="closeModal"
                            class="flex-1 sm:flex-none px-5 py-2.5 rounded-lg font-medium transition-all duration-200"
                            style="
                                background-color: var(--period-detail-btn-cancel-bg);
                                    color: var(--period-detail-btn-cancel-text);
                            "
                            onmouseover="this.style.transform='translateY(-2px)'; this.style.filter='brightness(1.1)';"
                                onmouseout="this.style.transform='translateY(0)'; this.style.filter='brightness(1)';">
                            <span class="flex items-center gap-2">
                                Cerrar
                            </span>
                        </button>
                        @endif
                    </div>

                    {{-- Botones de Acción Principal --}}
                    <div class="flex gap-2 w-full sm:w-auto">
                        @if($editMode)
                            <button
                                wire:click="updateStudent"
                                class="flex-1 sm:flex-none px-6 py-2.5 rounded-lg font-semibold text-white transition-all duration-200 shadow-lg"
                                style="background: linear-gradient(135deg, var(--period-detail-brand-primary) 0%, var(--period-detail-brand-secondary) 100%);"
                                onmouseover="this.style.transform='translateY(-2px)'; this.style.filter='brightness(1.1)';"
                                onmouseout="this.style.transform='translateY(0)'; this.style.filter='brightness(1)';">
                                <span class="flex items-center gap-2">
                                    Guardar
                                </span>
                            </button>
                        @else
                            <button
                                wire:click="editStudent({{ $selectedStudent->id }})"
                                class="flex-1 sm:flex-none px-6 py-2.5 rounded-lg font-semibold text-white transition-all duration-200 shadow-lg"
                                style="background: linear-gradient(135deg, var(--period-detail-brand-primary) 0%, var(--period-detail-brand-secondary) 100%);"
                                onmouseover="this.style.transform='translateY(-2px)'; this.style.filter='brightness(1.1)';"
                                onmouseout="this.style.transform='translateY(0)'; this.style.filter='brightness(1)';">
                                <span class="flex items-center gap-2">
                                    Editar
                                </span>
                            </button>

                            <button
                                wire:click="approve({{ $selectedStudent->id }})"
                                class="flex-1 sm:flex-none px-5 py-2.5 rounded-lg font-semibold transition-all duration-200 shadow-md"
                                style="
                                    background-color: var(--period-detail-btn-approve-bg);
                                    color: var(--period-detail-btn-approve-text);
                                "
                                onmouseover="this.style.transform='translateY(-2px)'; this.style.filter='brightness(1.1)';"
                                onmouseout="this.style.transform='translateY(0)'; this.style.filter='brightness(1)';">
                                <span class="flex items-center gap-2">
                                    Aprobar
                                </span>
                            </button>

                            <button
                                wire:click="reject({{ $selectedStudent->id }})"
                                class="flex-1 sm:flex-none px-5 py-2.5 rounded-lg font-semibold transition-all duration-200 shadow-md"
                                style="
                                    background-color: var(--period-detail-btn-reject-bg);
                                    color: var(--period-detail-btn-reject-text);
                                "
                                onmouseover="this.style.transform='translateY(-2px)'; this.style.filter='brightness(1.1)'; "
                                onmouseout="this.style.transform='translateY(0)'; this.style.filter='brightness(1)';">
                                <span class="flex items-center gap-2">
                                    Rechazar
                                </span>
                            </button>
                        @endif
                    </div>
                </div>

            </div>
        </flux:modal>
    @endif

    {{-- ================== MODAL DE VISTA PREVIA GESTIÓN DE ESTUDIANTES RECHAZO ================== --}}
    @if($showRejectModal && $selectedStudent)
        <flux:modal 
            wire:model="showRejectModal" 
            :dismissible="false"
            class="w-[95vw] sm:w-[85vw] md:w-[600px] lg:w-[650px] max-w-[95vw]">
            <div class="flex flex-col max-h-[85vh]">

                {{-- Header con diseño consistente --}}
                <div 
                    class="relative px-6 py-5 flex items-center gap-4 overflow-hidden flex-shrink-0 rounded-t-xl border-b"
                    style="
                        background: linear-gradient(
                            135deg,
                            var(--period-detail-card-header-bg) 0%,
                            var(--period-detail-card-header-bg-2) 100%
                        );
                        border-bottom: 1px solid var(--period-detail-border);
                    ">
                    {{-- Información del estudiante --}}
                    <div class="flex-1 min-w-0">
                        <h3 class="text-lg font-bold" style="color: var(--period-detail-text-primary);">
                            Rechazar Estudiante
                        </h3>
                        <p class="text-sm truncate" style="color: var(--period-detail-text-secondary);">
                            {{ $selectedStudent->name }}
                            {{ $selectedStudent->last_name_paterno }}
                            {{ $selectedStudent->last_name_materno }}
                        </p>
                    </div>
                </div>

                {{-- Contenido del modal --}}
                <div class="flex-1 overflow-y-auto p-6" style="background-color: var(--period-detail-card-bg);">
                    <div class="space-y-4">

                        {{-- Mensaje informativo --}}
                        <div 
                            class="p-4 rounded-lg border"
                            style="
                                background-color: rgba(239, 68, 68, 0.1);
                                border-color: rgba(239, 68, 68, 0.3);
                            ">
                            <div class="flex gap-3">
                                <svg class="w-5 h-5 flex-shrink-0 mt-0.5" style="color: var(--period-detail-status-rejected-text);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <div class="flex-1">
                                    <p class="text-sm font-semibold mb-1" style="color: var(--period-detail-status-rejected-text);">
                                        Atención
                                    </p>
                                    <p class="text-xs" style="color: var(--period-detail-text-secondary);">
                                        Esta acción rechazará el registro del estudiante. Por favor, proporciona un motivo detallado.
                                    </p>
                                </div>
                            </div>
                        </div>

                        {{-- Campo de motivo --}}
                        <div>
                            <label class="block text-sm font-semibold mb-2" style="color: var(--period-detail-text-primary);">
                                Motivo del rechazo <span style="color: var(--period-detail-status-rejected-text);">*</span>
                            </label>
                            <textarea
                                class="w-full px-4 py-3 rounded-lg border text-sm resize-none transition-all focus:ring-2 focus:ring-opacity-50"
                                style="
                                    background-color: var(--period-detail-content-bg);
                                    border-color: var(--period-detail-border);
                                    color: var(--period-detail-text-primary);
                                "
                                rows="5"
                                placeholder="Describe el motivo del rechazo de manera clara y profesional..."
                                wire:model="rejectionReason"
                            ></textarea>
                            @error('rejectionReason')
                                <p class="mt-2 text-sm flex items-center gap-2" style="color: #ee6e6c;">
                                    <!-- Triángulo relleno con ! -->
                                    <svg class="w-5 h-5 flex-shrink-0" viewBox="0 0 20 20" fill="#ee6e6c">
                                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.72-1.36 3.485 0l6.518 11.59c.75 1.335-.213 2.971-1.742 2.971H3.48c-1.529 0-2.492-1.636-1.742-2.971L8.257 3.1zM11 13a1 1 0 10-2 0 1 1 0 002 0zm-1-8a1 1 0 00-.993.883L9 6v4a1 1 0 001.993.117L11 10V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                            <p class="mt-1.5 text-xs" style="color: var(--period-detail-text-secondary);">
                                Este motivo será visible para el estudiante.
                            </p>
                        </div>


                    </div>
                </div>

                {{-- Footer con botones --}}
                <div 
                    class="px-6 py-4 flex flex-col-reverse sm:flex-row items-center justify-end gap-3 border-t flex-shrink-0 rounded-b-xl"
                    style="
                        background-color: var(--period-detail-card-bg);
                        border-color: var(--period-detail-border);
                    ">
                    {{-- Botón Cancelar --}}
                    <button
                        wire:click="$set('showRejectModal', false)"
                        class="w-full sm:w-auto px-5 py-2.5 rounded-lg font-medium transition-all duration-200"
                        style="
                            background-color: var(--period-detail-btn-cancel-bg);
                            color: var(--period-detail-btn-cancel-text);
                        "
                        onmouseover="this.style.transform='translateY(-2px)'; this.style.filter='brightness(1.1)';"
                        onmouseout="this.style.transform='translateY(0)'; this.style.filter='brightness(1)';">
                        Cancelar
                    </button>

                    {{-- Botón Rechazar --}}
                    <button
                        wire:click="confirmReject"
                        class="w-full sm:w-auto px-6 py-2.5 rounded-lg font-semibold transition-all duration-200 shadow-lg"
                        style="
                            background-color: var(--period-detail-btn-reject-bg);
                            color: var(--period-detail-btn-reject-text);
                        "
                        onmouseover="this.style.transform='translateY(-2px)'; this.style.filter='brightness(1.1)';"
                        onmouseout="this.style.transform='translateY(0)'; this.style.filter='brightness(1)';">
                        <span class="flex items-center justify-center gap-2">
                            Confirmar
                        </span>
                    </button>
                </div>

            </div>
        </flux:modal>
    @endif


    {{-- ================== MODAL DE VISTA PREVIA DOCUMENTOS BASE================== --}}
    @if($previewPath)
        <flux:modal 
            wire:model="previewPath" 
            :dismissible="false"
            class="w-[95vw] sm:w-[90vw] md:w-[85vw] lg:w-[1200px] xl:w-[1400px] max-w-[95vw]">
            <div class="flex flex-col h-[90vh] max-h-[95vh]">
                @php
                    $ext = strtolower(pathinfo($previewPath, PATHINFO_EXTENSION));
                @endphp

                {{-- Header Minimalista --}}
                <div 
                    class="px-6 py-4 flex items-center justify-between border-b flex-shrink-0 rounded-t-xl"
                    style="
                        background: linear-gradient(
                            135deg,
                            var(--period-detail-card-header-bg) 0%,
                            var(--period-detail-card-header-bg-2) 100%
                        );
                        border-bottom: 1px solid var(--period-detail-border);
                    ">
                    <div class="flex items-center gap-3">
                        @if($ext === 'pdf')
                            <div class="w-10 h-10 rounded-lg flex items-center justify-center"
                                style="background-color: var(--period-detail-content-bg);">
                                <svg class="w-5 h-5" style="color: var(--period-detail-accent);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                </svg>
                            </div>
                        @else
                            <div class="w-10 h-10 rounded-lg flex items-center justify-center"
                                style="background-color: var(--period-detail-content-bg);">
                                <svg class="w-5 h-5" style="color: var(--period-detail-accent);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                            </div>
                        @endif
                        <div>
                            <h3 class="font-semibold" style="color: var(--period-detail-text-primary);">
                                {{ $previewName }}
                            </h3>
                            <p class="text-sm" style="color: var(--period-detail-text-secondary);">
                                Documento • {{ strtoupper($ext) }}
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Área de Contenido --}}
                <div class="flex-1 overflow-hidden {{ $ext !== 'pdf' ? 'rounded-b-xl' : '' }}" style="background-color: var(--period-detail-card-bg); ">
                    @if($ext === 'pdf')
                        {{-- Vista PDF Full Screen --}}
                        <iframe 
                            src="{{ asset($previewPath) }}" 
                            class="w-full h-full"
                            style="background-color: #1e293b;"
                            title="Vista previa PDF">
                        </iframe>
                    @else
                        {{-- Estado Vacío Centrado --}}
                        <div class="w-full h-full flex flex-col items-center justify-center p-8">
                            <div class="max-w-md text-center space-y-6">
                                {{-- Icono Grande --}}
                                <div class="inline-flex items-center justify-center w-24 h-24 rounded-2xl"
                                    style="background-color: var(--period-detail-content-bg);">
                                    <svg class="w-12 h-12" style="color: var(--period-detail-text-secondary);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                </div>

                                {{-- Mensaje --}}
                                <div class="space-y-2">
                                    <h4 class="text-lg font-semibold" style="color: var(--period-detail-text-primary);">
                                        Vista previa no disponible
                                    </h4>
                                    <p class="text-sm" style="color: var(--period-detail-text-secondary);">
                                        Este formato de archivo no puede ser visualizado en el navegador
                                    </p>
                                </div>

                                {{-- Botón de Descarga --}}
                                <a 
                                    href="{{ asset($previewPath) }}" 
                                    download="{{ $previewName }}"
                                    class="
                                        inline-flex items-center justify-center gap-2
                                        px-4 py-2.5
                                        text-white text-sm font-semibold
                                        rounded-lg
                                        shadow-sm
                                        transition-all duration-200
                                        hover:-translate-y-0.5
                                        hover:shadow-lg
                                    "
                                    style="
                                        background: linear-gradient(
                                            135deg,
                                            var(--period-detail-brand-primary) 0%,
                                            var(--period-detail-brand-secondary) 100%
                                        );
                                    "
                                    onmouseover="this.style.filter='brightness(1.1)';"
                                    onmouseout="this.style.filter='brightness(1)';"
                                >
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                    </svg>
                                    Descargar
                                </a>

                            </div>
                        </div>
                    @endif
                </div>

                {{-- Footer con Acciones (solo para PDF) --}}
                @if($ext === 'pdf')
                    <div 
                        class="px-6 py-4 flex items-center justify-between border-t flex-shrink-0 rounded-b-xl"
                        style="
                            background-color: var(--period-detail-card-bg);
                            border-color: var(--period-detail-border);
                        ">
                        <p class="text-sm" style="color: var(--period-detail-text-secondary);">
                            Usa los controles del visor para navegar por el documento
                        </p>
                        
                        <div class="flex items-center gap-3">
                            <a 
                                href="{{ asset($previewPath) }}" 
                                download="{{ $previewName }}"
                                class="inline-flex items-center gap-2 px-4 py-2 rounded-lg font-medium text-white transition-all duration-200"
                                style="background: linear-gradient(135deg, var(--period-detail-brand-primary) 0%, var(--period-detail-brand-secondary) 100%);"
                                onmouseover="this.style.transform='translateY(-1px)';"
                                onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none';"
                            >
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                </svg>
                                Descargar
                            </a>

                            
                            <button
                                wire:click="$set('previewPath', null)"
                                class="
                                    px-6 py-2
                                    bg-[var(--period-detail-btn-cancel-bg)]!
                                    text-[var(--period-detail-btn-cancel-text)]!
                                    font-medium
                                    flex items-center justify-center gap-2
                                    rounded-lg
                                    border border-transparent
                                    shadow-sm
                                    transition-all duration-200
                                    hover:-translate-y-0.5
                                    hover:shadow-lg
                                "
                                onmouseover="this.style.filter='brightness(1.05)'"
                                onmouseout="this.style.filter='brightness(1)'"
                            >
                                Cerrar
                            </button>


                        </div>
                    </div>
                @endif
            </div>
        </flux:modal>
    @endif

    <flux:modal 
        wire:model="isDeleteDocumentModalOpen" 
        :dismissible="false"
        class="w-[95vw] sm:w-[450px] max-w-[95vw]">
        
        <div class="flex flex-col">
            {{-- Contenido --}}
            <div class="px-6 py-6" style="background-color: var(--period-detail-card-bg);">
                <div 
                    class="p-4 rounded-xl border"
                    style="background-color: var(--period-detail-content-bg);
                        border-color: var(--period-detail-border);">
                    <div class="flex items-start gap-3">
                        <div class="flex-shrink-0 p-2 rounded-lg" style="background-color: var(--period-detail-card-bg);">
                            <svg class="w-5 h-5" style="color: var(--period-detail-status-rejected-text);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-medium mb-1" style="color: var(--period-detail-text-primary);">
                                ¿Está seguro de que desea eliminar este documento?
                            </p>
                            <p class="text-xs" style="color: var(--period-detail-text-secondary);">
                                Esta acción es irreversible.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Footer con botones --}}
            <div 
                class="px-6 py-4 flex flex-col-reverse sm:flex-row items-center justify-end gap-3 border-t flex-shrink-0 rounded-b-xl"
                style="background-color: var(--period-detail-card-bg);
                    border-color: var(--period-detail-border);">

                {{-- Cancelar --}}
                <button
                    wire:click="$set('isDeleteDocumentModalOpen', false)"
                    class="w-full sm:w-auto px-5 py-2.5 rounded-lg font-medium transition-all duration-200"
                    style="background-color: var(--period-detail-btn-cancel-bg); color: var(--period-detail-btn-cancel-text);">
                    Cancelar
                </button>

                {{-- Eliminar --}}
                <button
                    wire:click="confirmDeleteDocument"
                    class="w-full sm:w-auto px-6 py-2.5 rounded-lg font-semibold transition-all duration-200 shadow-lg text-white"
                    style="background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);">
                    <span class="flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                        Eliminar
                    </span>
                </button>
            </div>
        </div>
    </flux:modal>

    <flux:modal 
        wire:model="isUploadModeChangeModalOpen" 
        :dismissible="false"
        class="w-[95vw] sm:w-[450px] max-w-[95vw]">

        <div class="flex flex-col">
            {{-- Contenido --}}
            <div class="px-6 py-6" style="background-color: var(--period-detail-card-bg);">
                <div 
                    class="p-4 rounded-xl border"
                    style="background-color: var(--period-detail-content-bg);
                        border-color: var(--period-detail-border);">
                    <div class="flex items-start gap-3">
                        <div class="flex-shrink-0 p-2 rounded-lg" style="background-color: var(--period-detail-card-bg);">
                            <svg class="w-5 h-5" style="color: var(--period-detail-status-rejected-text);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-medium mb-1" style="color: var(--period-detail-text-primary);">
                                Estás a punto de cambiar el modo de carga de este documento.
                            </p>
                            <p class="text-xs" style="color: var(--period-detail-text-secondary);">
                                Esto afectará todos los archivos subidos por el administrador para este documento.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Footer con botones --}}
            <div 
                class="px-6 py-4 flex flex-col-reverse sm:flex-row items-center justify-end gap-3 border-t flex-shrink-0 rounded-b-xl"
                style="background-color: var(--period-detail-card-bg);
                    border-color: var(--period-detail-border);">

                {{-- Cancelar --}}
                <button
                    wire:click="$set('isUploadModeChangeModalOpen', false)"
                    class="w-full sm:w-auto px-5 py-2.5 rounded-lg font-medium transition-all duration-200"
                    style="background-color: var(--period-detail-btn-cancel-bg); color: var(--period-detail-btn-cancel-text);">
                    Cancelar
                </button>

                {{-- Confirmar cambio --}}
                <button
                    wire:click="confirmUploadModeChange"
                    class="w-full sm:w-auto px-6 py-2.5 rounded-lg font-semibold transition-all duration-200 shadow-lg text-white"
                    style="background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);">
                    Confirmar
                </button>
            </div>
        </div>
    </flux:modal>




    {{-- ================== MODAL RÁPIDO DE REVISIÓN  ================== --}}
    {{-- ================== MODAL RÁPIDO DE REVISIÓN - ACTUALIZADO ================== --}}
    <flux:modal
        wire:model="showQuickReviewModal" 
        :dismissible="false"
        class="w-[95vw] sm:w-[90vw] md:w-[85vw] lg:w-[1200px] xl:w-[1400px] max-w-[95vw]">
        @if($quickReviewDoc)
        <div class="flex flex-col h-[90vh] max-h-[95vh]">
            {{-- Header revisión rápida --}}
            <div
                class="p-5 flex-shrink-0 rounded-2xl"
                style="
                    background: linear-gradient(
                        135deg,
                        var(--period-detail-card-header-bg) 0%,
                        var(--period-detail-card-header-bg-2) 100%
                    );
                    border-bottom: 1px solid var(--period-detail-border);
                "
            >
                <div class="flex items-center gap-4">
                    {{-- Texto --}}
                    <div class="flex-1 min-w-0">
                        <h2
                            class="text-lg font-bold truncate"
                            style="color: var(--period-detail-text-primary);"
                        >
                            {{ $quickReviewDoc->file->name ?? $quickReviewDoc->name }}
                        </h2>
                        <p
                            class="text-sm truncate"
                            style="color: var(--period-detail-text-secondary);"
                        >
                            {{ $quickReviewDoc->student->name }}
                            {{ $quickReviewDoc->student->last_name_paterno }}
                            {{ $quickReviewDoc->student->last_name_materno }}
                            • {{ $quickReviewDoc->student->control_number }}
                        </p>
                    </div>

                    {{-- Estado - ACTUALIZADO según upload_mode --}}
                    @if($quickReviewDoc->file->upload_mode === 'admin_only')
                        {{-- Para admin_only, mostrar si tiene archivo o no --}}
                        @if($quickReviewDoc->file->is_individual)
                            {{-- Individual: verificar en file_student_uploads --}}
                            @if($currentIndividualUpload)
                                <span
                                    class="inline-flex items-center gap-1 px-3 py-1 rounded-lg text-xs font-semibold border flex-shrink-0"
                                    style="
                                        background-color: rgba(16, 185, 129, 0.15);
                                        color: var(--period-detail-status-approved-icon-color);
                                        border-color: rgba(16, 185, 129, 0.3);
                                    "
                                >
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    Archivo Subido
                                </span>
                            @else
                                <span
                                    class="inline-flex items-center gap-1 px-3 py-1 rounded-lg text-xs font-semibold border flex-shrink-0"
                                    style="
                                        background-color: rgba(148, 163, 184, 0.15);
                                        color: #64748b;
                                        border-color: rgba(148, 163, 184, 0.3);
                                    "
                                >
                                    Sin Subir
                                </span>
                            @endif
                        @else
                            {{-- No individual: archivo base --}}
                            <span
                                class="inline-flex items-center gap-1 px-3 py-1 rounded-lg text-xs font-semibold border flex-shrink-0"
                                style="
                                    background-color: rgba(16, 185, 129, 0.15);
                                    color: var(--period-detail-status-approved-icon-color);
                                    border-color: rgba(16, 185, 129, 0.3);
                                "
                            >
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                Documento Base
                            </span>
                        @endif
                    @else
                        {{-- user_only o bidirectional: mostrar estado normal --}}
                        @if($quickReviewDoc->student_file_path)
                            <span
                                class="inline-flex items-center gap-1 px-3 py-1 rounded-lg text-xs font-semibold border flex-shrink-0"
                                style="
                                    @if($quickReviewDoc->status === 'revisado')
                                        background-color: rgba(16, 185, 129, 0.15);
                                        color: var(--period-detail-status-approved-icon-color);
                                        border-color: rgba(16, 185, 129, 0.3);
                                    @elseif($quickReviewDoc->status === 'rechazado')
                                        background-color: rgba(239, 68, 68, 0.15);
                                        color: var(--period-detail-status-rejected-text);
                                        border-color: rgba(239, 68, 68, 0.3);
                                    @else
                                        background-color: rgba(232, 210, 50, 0.15);
                                        color: var(--period-detail-status-pending);
                                        border-color: rgba(232, 210, 50, 0.3);
                                    @endif
                                "
                            >
                                @if($quickReviewDoc->status === 'revisado')
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    Aprobado
                                @elseif($quickReviewDoc->status === 'rechazado')
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                    Rechazado
                                @else
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    En revisión
                                @endif
                            </span>
                        @else
                            <span
                                class="inline-flex items-center gap-1 px-3 py-1 rounded-lg text-xs font-semibold border flex-shrink-0"
                                style="
                                    background-color: rgba(148, 163, 184, 0.15);
                                    color: #64748b;
                                    border-color: rgba(148, 163, 184, 0.3);
                                "
                            >
                                Pendiente
                            </span>
                        @endif
                    @endif
                </div>
            </div>

            {{-- Layout de 2 columnas optimizado --}}
            <div class="grid grid-cols-1 lg:grid-cols-[1fr_340px] xl:grid-cols-[1fr_380px] gap-4 p-4 flex-1 overflow-hidden">
                
                {{-- Columna izquierda: Preview --}}
                <div class="flex flex-col gap-3 min-h-0">
                    <div class="
                        bg-[var(--period-detail-card-bg)]
                        rounded-xl
                        border border-[var(--period-detail-border)]
                        flex-1 flex flex-col min-h-0
                        overflow-hidden
                    ">

                        {{-- ================= ADMIN ONLY + NO INDIVIDUAL ================= --}}
                        @if($quickReviewDoc->file->upload_mode === 'admin_only' && !$quickReviewDoc->file->is_individual)

                            {{-- ===== PDF DE EJEMPLO (PREVIEW) ===== --}}
                            @if($adminExamplePdf)
                                <div class="flex-1 border-b border-[var(--period-detail-border)]">
                                    <iframe
                                        src="{{ $adminExamplePdf['url'] }}"
                                        class="w-full h-full bg-white"
                                    ></iframe>
                                </div>
                            @endif

                            {{-- ===== WORD BASE (DESCARGA) ===== --}}
                            @if($adminBaseWord)
                                <div class="p-4 flex items-center justify-between gap-4">
                                    <div class="flex items-center gap-3 min-w-0">
                                        <svg class="w-8 h-8 text-blue-600 flex-shrink-0" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M4 4h16v16H4z"/>
                                        </svg>

                                        <div class="min-w-0">
                                            <p class="text-sm font-semibold truncate">
                                                Documento base (Word)
                                            </p>
                                            <p class="text-xs text-[var(--period-detail-text-secondary)] truncate">
                                                {{ $adminBaseWord['name'] }}
                                            </p>
                                        </div>
                                    </div>

                                    <a
                                        href="{{ $adminBaseWord['url'] }}"
                                        download
                                        class="text-sm font-semibold text-[var(--period-detail-accent)] hover:underline"
                                    >
                                        Descargar
                                    </a>
                                </div>
                            @endif

                            {{-- ===== NADA CARGADO ===== --}}
                            @if(!$adminExamplePdf && !$adminBaseWord)
                                <div class="flex flex-col items-center justify-center flex-1 text-center px-4">
                                    <p class="text-sm text-[var(--period-detail-text-secondary)]">
                                        No hay documentos base disponibles
                                    </p>
                                </div>
                            @endif


                        {{-- ================= RESTO DE CASOS ================= --}}
                        @else

                            @if($quickReviewPreviewUrl)
                                @if(pathinfo($quickReviewPreviewUrl, PATHINFO_EXTENSION) === 'pdf')
                                    <iframe
                                        src="{{ $quickReviewPreviewUrl }}"
                                        class="w-full h-full bg-white"
                                    ></iframe>
                                @else
                                    <div class="
                                        flex flex-col items-center justify-center flex-1
                                        border border-dashed
                                        border-[var(--period-detail-border)]
                                        rounded-lg
                                        m-2
                                        text-center
                                    ">
                                        <svg class="w-12 h-12 mb-3 text-[var(--period-detail-text-secondary)]"
                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                        </svg>

                                        <p class="text-sm text-[var(--period-detail-text-secondary)] mb-1">
                                            Vista previa no disponible
                                        </p>

                                        <a href="{{ $quickReviewPreviewUrl }}"
                                            download
                                            class="text-xs font-semibold text-[var(--period-detail-accent)] hover:underline">
                                            Descargar para ver
                                        </a>
                                    </div>
                                @endif
                            @else
                                <div class="flex flex-col items-center justify-center flex-1 text-center px-4">
                                    <p class="text-sm text-[var(--period-detail-text-secondary)]">
                                        No se ha cargado ningún documento
                                    </p>
                                </div>
                            @endif

                        @endif
                    </div>
                </div>

                {{-- Columna derecha: Acciones --}}
                <div class="flex flex-col gap-3 overflow-y-auto min-h-0">
                    
                    {{-- NUEVO: Subir archivo individual (solo si es admin_only + is_individual) --}}
                    @if($quickReviewDoc->file->upload_mode === 'admin_only' && $quickReviewDoc->file->is_individual)
                        <div class="bg-[var(--period-detail-card-bg)] rounded-xl p-3 border border-[var(--period-detail-border)]">
                            <label class="block text-sm font-semibold text-[var(--period-detail-text-primary)] mb-2 flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5 text-[var(--period-detail-accent)]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                                </svg>
                                Subir Documento 
                            </label>

                            @if($currentIndividualUpload)
                                {{-- Ya existe un archivo --}}
                                <div class="mb-2 p-2 rounded-lg border border-[var(--period-detail-border)] bg-[var(--period-detail-content-bg)] flex items-center justify-between">
                                    <div class="flex-1 min-w-0">
                                        <p class="text-xs font-medium text-[var(--period-detail-text-primary)] truncate">
                                            {{ $currentIndividualUpload->name_file }}
                                        </p>
                                        <p class="text-xs text-[var(--period-detail-text-secondary)]">
                                            {{ $currentIndividualUpload->created_at->diffForHumans() }}
                                        </p>
                                    </div>
                                    <button
                                        wire:click="deleteIndividualFile"
                                        wire:confirm="¿Eliminar este archivo?"
                                        class="ml-2 p-1.5 rounded hover:bg-red-100 text-red-600"
                                        title="Eliminar archivo"
                                    >
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </div>
                            @endif

                            {{-- Input de archivo --}}
                            <div class="space-y-2">
                                <input
                                    type="file"
                                    wire:model="individualUploadFile"
                                    accept=".pdf,.doc,.docx"
                                    class="
                                        w-full text-sm
                                        file:mr-4 file:py-2 file:px-4
                                        file:rounded-lg file:border-0
                                        file:text-sm file:font-semibold
                                        file:cursor-pointer
                                        cursor-pointer
                                    "
                                    style="
                                        color: var(--period-detail-text-primary);
                                        file:bg-gradient-to-r file:from-[var(--period-detail-brand-primary)] file:to-[var(--period-detail-brand-secondary)];
                                        file:text-white;
                                    "
                                >
                                @error('individualUploadFile')
                                    <p class="text-sm flex items-center gap-2" style="color: #ee6e6c;">
                                        <svg class="w-5 h-5 flex-shrink-0" viewBox="0 0 20 20" fill="#ee6e6c">
                                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.72-1.36 3.485 0l6.518 11.59c.75 1.335-.213 2.971-1.742 2.971H3.48c-1.529 0-2.492-1.636-1.742-2.971L8.257 3.1zM11 13a1 1 0 10-2 0 1 1 0 002 0zm-1-8a1 1 0 00-.993.883L9 6v4a1 1 0 001.993.117L11 10V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                        </svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            @if($individualUploadFile)
                                <flux:button
                                    wire:click="uploadIndividualFile"
                                    wire:loading.attr="disabled"
                                    class="w-full mt-2 bg-[var(--period-detail-btn-approve-bg)]! text-[var(--period-detail-btn-approve-text)]!"
                                >
                                    <span wire:loading.remove wire:target="uploadIndividualFile">
                                        Subir Archivo
                                    </span>
                                    <span wire:loading wire:target="uploadIndividualFile">
                                        Subiendo...
                                    </span>
                                </flux:button>
                            @endif
                        </div>
                    @endif

                    {{-- Comentarios (siempre visibles) --}}
                    <div class="bg-[var(--period-detail-card-bg)] rounded-xl p-3 border border-[var(--period-detail-border)] flex-1 flex flex-col min-h-0">
                        <label class="block text-sm font-semibold text-[var(--period-detail-text-primary)] mb-1 flex items-center gap-1.5 flex-shrink-0">
                            <svg class="w-3.5 h-3.5 text-[var(--period-detail-accent)]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/>
                            </svg>
                            Observaciones
                        </label>

                        <p class="text-sm text-[var(--period-detail-text-secondary)] mb-2">
                            Este comentario es visible para el estudiante.
                        </p>

                        <textarea 
                            wire:model.defer="quickReviewComments"
                            placeholder="Escribe aquí..."
                            class="
                                w-full flex-1 px-2.5 py-2
                                bg-[var(--period-detail-content-bg)]
                                border rounded-lg
                                text-[var(--period-detail-text-primary)]
                                text-sm
                                resize-none
                                min-h-0
                                focus:outline-none
                                focus:ring-1
                                border-[var(--period-detail-border)]
                                focus:ring-[var(--period-detail-accent)]
                            "
                        ></textarea>

                        @error('quickReviewComments')
                            <p class="mt-2 text-sm flex items-center gap-2" style="color: #ee6e6c;">
                                <svg class="w-5 h-5 flex-shrink-0" viewBox="0 0 20 20" fill="#ee6e6c">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.72-1.36 3.485 0l6.518 11.59c.75 1.335-.213 2.971-1.742 2.971H3.48c-1.529 0-2.492-1.636-1.742-2.971L8.257 3.1zM11 13a1 1 0 10-2 0 1 1 0 002 0zm-1-8a1 1 0 00-.993.883L9 6v4a1 1 0 001.993.117L11 10V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror

                        {{-- Botón para guardar comentarios sin cambiar estado --}}
                        <flux:button
                            wire:click="saveComments"
                            variant="ghost"
                            size="sm"
                            class="mt-2"
                        >
                            Guardar
                        </flux:button>
                    </div>

                    {{-- Fecha límite personalizada (solo si NO es admin_only) --}}
                    @if($quickReviewDoc->file->upload_mode !== 'admin_only')
                        @php
                            $periodStart = optional($quickReviewDoc->file->period)->start_date;
                            $periodEnd   = optional($quickReviewDoc->file->period)->end_date;
                        @endphp

                        <div class="bg-[var(--period-detail-card-bg)] rounded-xl p-2.5 border border-[var(--period-detail-border)] flex flex-col min-h-0">
                            <flux:field>
                                <flux:label class="flex items-center mb-0.5">
                                    <svg class="w-4 h-4 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                        style="color: var(--period-detail-brand-primary);">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    <span>Fecha Límite Personalizada</span>
                                </flux:label>

                                <flux:input 
                                    type="date"
                                    size="sm"
                                    wire:model="editingDates.{{ $quickReviewDoc->id }}"
                                    wire:change="updateDocumentDate({{ $quickReviewDoc->id }})"

                                    {{-- rango permitido --}}
                                    min="{{ $periodStart ? \Carbon\Carbon::parse($periodStart)->format('Y-m-d') : '' }}"
                                    max="{{ $periodEnd ? \Carbon\Carbon::parse($periodEnd)->format('Y-m-d') : '' }}"

                                    style="background-color: var(--period-detail-content-bg); 
                                        color: var(--period-detail-text-primary);
                                        font-size: 0.95rem;"
                                    class="w-full px-4 py-2 rounded-xl transition-all"
                                />
                            </flux:field>
                        </div>
                    @endif


                    {{-- Botones de acción --}}
                    <div class="space-y-3 flex-shrink-0">
                        {{-- Aprobar y Rechazar --}}
                        @if($quickReviewDoc->file->upload_mode !== 'admin_only' && $quickReviewDoc->student_file_path)
                            @if($quickReviewDoc->student_file_path || $currentIndividualUpload)
                                <div class="grid grid-cols-2 gap-3">
                                    <flux:button 
                                        wire:click="quickApproveDocument"
                                        class="
                                            w-full
                                            bg-[var(--period-detail-btn-approve-bg)]!
                                            text-[var(--period-detail-btn-approve-text)]!
                                            font-semibold
                                            flex items-center justify-center gap-2
                                            rounded-lg
                                            border border-transparent
                                            shadow-sm shadow-[var(--period-detail-btn-primary-shadow)]!
                                            transition-all duration-200
                                            hover:-translate-y-0.5
                                            hover:shadow-lg
                                        "
                                        onmouseover="this.style.filter='brightness(1.1)'"
                                        onmouseout="this.style.filter='brightness(1)'"
                                    >
                                        Aprobar
                                    </flux:button>

                                    <flux:button 
                                        wire:click="quickRejectDocument"
                                        class="
                                            w-full
                                            bg-[var(--period-detail-btn-reject-bg)]!
                                            text-[var(--period-detail-btn-reject-text)]!
                                            font-semibold
                                            flex items-center justify-center gap-2
                                            rounded-lg
                                            border border-transparent
                                            shadow-sm shadow-[var(--period-detail-btn-primary-shadow)]!
                                            transition-all duration-200
                                            hover:-translate-y-0.5
                                            hover:shadow-lg
                                        "
                                        onmouseover="this.style.filter='brightness(1.1)'"
                                        onmouseout="this.style.filter='brightness(1)'"
                                    >
                                        Rechazar
                                    </flux:button>
                                </div>
                            @endif
                        @endif
                    </div>

                    {{-- Navegación entre documentos --}}
                    @if($nextPendingDoc || $previousPendingDoc)
                        <flux:separator />
                        <div class="flex gap-2 flex-shrink-0">
                            <flux:button 
                                wire:click="navigateToPreviousDoc"
                                variant="ghost"
                                size="sm"
                                :disabled="!$previousPendingDoc"
                                class="flex-1">
                                ← Anterior
                            </flux:button>
                            <flux:button 
                                wire:click="navigateToNextDoc"
                                variant="ghost"
                                size="sm"
                                :disabled="!$nextPendingDoc"
                                class="flex-1">
                                Siguiente →
                            </flux:button>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        @endif
    </flux:modal>
    

    

</div>