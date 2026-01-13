<div class="min-h-screen bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900 p-6">

    {{-- Header --}}
    <div class="max-w-7xl mx-auto mb-8">
        <button
            wire:click="goBack"
            class="flex items-center gap-2 text-gray-400 hover:text-white mb-6 transition-all duration-200 group"
        >
            <svg class="w-5 h-5 group-hover:-translate-x-1 transition-transform"
                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M15 19l-7-7 7-7" />
            </svg>
            <span class="font-medium">Volver a periodos</span>
        </button>

        <div class="flex items-start justify-between">
            <div class="flex-1">
                <h1 class="text-5xl font-bold mb-3 bg-gradient-to-r from-blue-400 via-purple-400 to-pink-400 bg-clip-text text-transparent">
                    {{ $period->name }}
                </h1>
                <div class="flex items-center gap-3 text-slate-400">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <p class="text-base">
                        {{ \Carbon\Carbon::parse($period->start_date)->translatedFormat('d M') }} - 
                        {{ \Carbon\Carbon::parse($period->end_date)->translatedFormat('d M Y') }}
                    </p>
                </div>
            </div>
            @if($period->is_active)
                <div class="bg-emerald-500/10 backdrop-blur-sm px-5 py-2.5 rounded-full border border-emerald-500/30">
                    <div class="flex items-center gap-2">
                        <span class="relative flex h-2 w-2">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                        </span>
                        <span class="font-semibold text-emerald-400 text-sm">Activo</span>
                    </div>
                </div>
            @endif
        </div>
    </div>

    {{-- Quick Stats Bar --}}
    <div class="max-w-7xl mx-auto mb-6">
        <div class="grid gap-4 [grid-template-columns:repeat(auto-fit,minmax(220px,1fr))]">

            <!-- Total Estudiantes -->
            <div class="h-full bg-gradient-to-br from-blue-600 to-blue-700 rounded-xl p-5 shadow-lg border border-blue-500/30
                        transition-transform duration-200 hover:scale-[1.02]">
                <div class="flex items-center justify-between h-full">
                    <div>
                        <p class="text-blue-200 text-sm mb-1">Total Estudiantes</p>
                        <p class="text-3xl font-bold text-white">
                            {{ $period->students->count() }}
                        </p>
                    </div>
                    <div class="w-12 h-12 bg-white/10 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Aprobados -->
            <div class="h-full bg-gradient-to-br from-emerald-600 to-emerald-700 rounded-xl p-5 shadow-lg border border-emerald-500/30
                        transition-transform duration-200 hover:scale-[1.02]">
                <div class="flex items-center justify-between h-full">
                    <div>
                        <p class="text-emerald-200 text-sm mb-1">Aprobados</p>
                        <p class="text-3xl font-bold text-white">
                            {{ $period->students->where('status','aprobado')->count() }}
                        </p>
                    </div>
                    <div class="w-12 h-12 bg-white/10 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Pendientes -->
            <div class="h-full bg-gradient-to-br from-amber-600 to-amber-700 rounded-xl p-5 shadow-lg border border-amber-500/30
                        transition-transform duration-200 hover:scale-[1.02]">
                <div class="flex items-center justify-between h-full">
                    <div>
                        <p class="text-amber-200 text-sm mb-1">Pendientes</p>
                        <p class="text-3xl font-bold text-white">
                            {{ $period->students->where('status','pendiente')->count() }}
                        </p>
                    </div>
                    <div class="w-12 h-12 bg-white/10 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Rechazados -->
            <div class="h-full bg-gradient-to-br from-red-600 to-red-700 rounded-xl p-5 shadow-lg border border-red-500/30
                        transition-transform duration-200 hover:scale-[1.02]">
                <div class="flex items-center justify-between h-full">
                    <div>
                        <p class="text-red-200 text-sm mb-1">Rechazados</p>
                        <p class="text-3xl font-bold text-white">
                            {{ $period->students->where('status','rechazado')->count() }}
                        </p>
                    </div>
                    <div class="w-12 h-12 bg-white/10 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Documentos Base -->
            <div class="h-full bg-gradient-to-br from-purple-600 to-purple-700 rounded-xl p-5 shadow-lg border border-purple-500/30
                        transition-transform duration-200 hover:scale-[1.02]">
                <div class="flex items-center justify-between h-full">
                    <div>
                        <p class="text-purple-200 text-sm mb-1">Documentos Base</p>
                        <p class="text-3xl font-bold text-white">
                            {{ $period->files->count() }}
                        </p>
                    </div>
                    <div class="w-12 h-12 bg-white/10 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                </div>
            </div>

        </div>
    </div>

    {{-- Navigation Tabs --}}
    <div class="max-w-7xl mx-auto mb-6">
        <div class="bg-slate-800/50 backdrop-blur-xl rounded-2xl p-2 border border-slate-700/50 inline-flex gap-2">
            @foreach($tabs as $key => $data)
                <button
                    wire:click="setTab('{{ $key }}')"
                    class="flex items-center gap-2 px-6 py-3 font-medium rounded-xl transition-all duration-200
                    {{ $activeTab === $key
                        ? 'bg-blue-600 text-white shadow-lg'
                        : 'text-slate-400 hover:text-white hover:bg-slate-700/50' }}"
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
    <div class="max-w-7xl mx-auto">
        
        {{-- GESTIÓN DE ESTUDIANTES --}}
        @if($activeTab === 'estudiantes')
            <div class="bg-slate-800/50 backdrop-blur-xl rounded-2xl border border-slate-700/50 shadow-2xl overflow-hidden">

                {{-- Header --}}
                <div class="p-6 border-b border-slate-700/50">
                    <h2 class="text-2xl font-bold text-white mb-2">Gestión de Estudiantes</h2>
                    <p class="text-slate-400">Revisa el perfil de los estudiantes</p>

                    {{-- Buscador --}}
                    <div class="mt-4 max-w-sm">
                        <input
                            type="text"
                            wire:model.live="search"
                            placeholder="Buscar estudiantes..."
                            class="w-full bg-slate-900/60 border border-slate-700 rounded-lg px-4 py-2 text-sm text-slate-200 focus:ring-2 focus:ring-blue-500 focus:outline-none"
                        >
                    </div>
                </div>

                {{-- Tabla --}}
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-slate-900/50">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-slate-300 uppercase">Nombre</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-slate-300 uppercase">Avance Reticular</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-slate-300 uppercase">Estatus</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-slate-300 uppercase">Acciones</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-slate-700/50">
                            @forelse($students as $student)
                            <tr class="hover:bg-slate-700/30 transition">

                                {{-- Nombre --}}
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center text-white font-semibold">
                                            {{ strtoupper(substr($student->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <p class="text-slate-200 font-medium">
                                                {{ $student->name }} {{ $student->last_name_paterno }} {{ $student->last_name_materno }}
                                            </p>
                                            <p class="text-xs text-slate-400">{{ $student->control_number }}</p>
                                        </div>
                                    </div>
                                </td>

                                {{-- Avance --}}
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="flex-1">
                                            <div class="w-full bg-slate-700 rounded-full h-2 overflow-hidden">
                                                <div
                                                    class="bg-gradient-to-r from-blue-500 to-cyan-400 h-2 rounded-full"
                                                    style="width: {{ $student->reticular_progress }}%"
                                                ></div>
                                            </div>
                                        </div>
                                        <span class="text-sm text-slate-300 min-w-[3rem] text-right">
                                            {{ $student->reticular_progress }}%
                                        </span>
                                    </div>
                                </td>

                                {{-- Estatus --}}
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-3 py-1 text-xs font-semibold rounded-full {{ $student->status_class }}">
                                        {{ $student->status_label }}
                                    </span>
                                </td>

                                {{-- Acciones --}}
                                <td class="px-6 py-4">
                                    <button
                                        wire:click="viewDetails({{ $student->id }})"
                                        class="px-3 py-2 bg-slate-700 hover:bg-slate-600 text-slate-200 text-xs rounded-lg transition flex items-center gap-2"
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
                                <td colspan="4" class="px-6 py-12 text-center text-slate-500">
                                    No hay estudiantes registrados.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Paginación --}}
                <div class="p-4 border-t border-slate-700/50">
                    {{ $students->links() }}
                </div>
            </div>
        @endif


        {{-- DOCUMENTOS BASE --}}
        @if($activeTab === 'documentos')
            <div class="space-y-6">

                {{-- ================== CREAR / EDITAR DOCUMENTO ================== --}}
                <div class="bg-slate-800/50 backdrop-blur-xl rounded-2xl border border-slate-700/50 shadow-2xl overflow-hidden">
                    {{-- Header --}}
                    <div class="bg-gradient-to-r from-purple-600/10 to-pink-600/10 border-b border-slate-700/50 p-6">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-purple-500 to-pink-600 flex items-center justify-center shadow-lg">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                                <h2 class="text-xl font-bold text-white">
                                    {{ $editingDocumentId ? 'Editar Documento Base' : 'Crear Nuevo Documento Base' }}
                                </h2>
                                <p class="text-sm text-slate-400">
                                    {{ $editingDocumentId ? 'Actualiza la información del documento' : 'Los estudiantes deberán subir este documento' }}
                                </p>
                            </div>
                            @if($editingDocumentId)
                                <button wire:click="cancelEditDocument"
                                        class="px-4 py-2 bg-slate-700/50 hover:bg-slate-600/50 text-slate-300 rounded-lg transition-colors text-sm font-medium">
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
                            <div class="lg:col-span-2">
                                <label class="block text-sm font-semibold text-slate-300 mb-3 flex items-center gap-2">
                                    <svg class="w-4 h-4 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                    </svg>
                                    Nombre del Documento
                                    <span class="text-red-400">*</span>
                                </label>
                                <input type="text"
                                    wire:model.defer="documentName"
                                    class="w-full px-4 py-3 bg-slate-900/50 border border-slate-700 rounded-xl text-white placeholder-slate-500 focus:border-purple-500 focus:ring-2 focus:ring-purple-500/20 transition-all"
                                    placeholder="Ej: Anexo 10. Plan de Trabajo">
                                @error('documentName')
                                    <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Fecha límite --}}
                            <div>
                                <label class="block text-sm font-semibold text-slate-300 mb-3 flex items-center gap-2">
                                    <svg class="w-4 h-4 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    Fecha Límite
                                </label>
                                <input type="date"
                                    wire:model.defer="documentDeadline"
                                    class="w-full px-4 py-3 bg-slate-900/50 border border-slate-700 rounded-xl text-white focus:border-purple-500 focus:ring-2 focus:ring-purple-500/20 transition-all">
                                @error('documentDeadline')
                                    <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Tamaño máximo --}}
                            <div>
                                <label class="block text-sm font-semibold text-slate-300 mb-3 flex items-center gap-2">
                                    <svg class="w-4 h-4 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4"/>
                                    </svg>
                                    Tamaño Máximo (KB)
                                </label>
                                <input type="number"
                                    wire:model.defer="maxSize"
                                    class="w-full px-4 py-3 bg-slate-900/50 border border-slate-700 rounded-xl text-white placeholder-slate-500 focus:border-purple-500 focus:ring-2 focus:ring-purple-500/20 transition-all"
                                    placeholder="10240">
                                @error('maxSize')
                                    <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Archivo principal --}}
                            <div class="lg:col-span-2">
                                <label class="block text-sm font-semibold text-slate-300 mb-3 flex items-center gap-2">
                                    <svg class="w-4 h-4 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                    Archivo del Documento (Word)
                                    @if(!$editingDocumentId)<span class="text-red-400">*</span>@endif
                                </label>
                                
                                <input type="file" wire:model="documentFile" accept=".doc,.docx" class="hidden" id="documentFile">
                                
                                <label for="documentFile"
                                    class="group flex items-center justify-center w-full px-6 py-8 bg-slate-900/50 border-2 border-dashed border-slate-700 rounded-xl cursor-pointer hover:border-purple-500 hover:bg-slate-900/80 transition-all">
                                    <div class="text-center">
                                        <svg class="w-12 h-12 mx-auto mb-3 text-slate-600 group-hover:text-purple-400 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                                        </svg>
                                        <p class="text-sm font-medium text-slate-400 group-hover:text-purple-400 transition-colors">
                                            Arrastra tu archivo o haz clic para seleccionar
                                        </p>
                                        <p class="text-xs text-slate-500 mt-1">DOC, DOCX hasta {{ $maxSize ?? 10240 }} KB</p>
                                    </div>
                                </label>

                                {{-- Mostrar archivo seleccionado --}}
                                @if ($documentFile)
                                    <div class="mt-3 p-3 bg-green-500/10 border border-green-500/30 rounded-lg flex items-center gap-3">
                                        <svg class="w-5 h-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        <span class="text-sm text-green-400 flex-1">{{ $documentFile->getClientOriginalName() }}</span>
                                        <button wire:click="$set('documentFile', null)" class="text-green-400 hover:text-green-300">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                            </svg>
                                        </button>
                                    </div>
                                @elseif($editingDocumentId && $period->files->find($editingDocumentId)?->name_file)
                                    <div class="mt-3 p-3 bg-slate-700/30 border border-slate-600/50 rounded-lg flex items-center gap-3">
                                        <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                        </svg>
                                        <span class="text-sm text-slate-400">Archivo actual: {{ $period->files->find($editingDocumentId)->name_file }}</span>
                                    </div>
                                @endif
                                @error('documentFile')
                                    <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Ejemplo (PDF) --}}
                            <div class="lg:col-span-2">
                                <label class="block text-sm font-semibold text-slate-300 mb-3 flex items-center gap-2">
                                    <svg class="w-4 h-4 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                    </svg>
                                    Ejemplo (Opcional, PDF)
                                    <span class="text-xs text-slate-500 font-normal">- Archivo de referencia para estudiantes</span>
                                </label>
                                
                                <input type="file" wire:model="documentExample" accept=".pdf" class="hidden" id="documentExample">
                                
                                <label for="documentExample"
                                    class="group flex items-center justify-center w-full px-6 py-6 bg-slate-900/50 border-2 border-dashed border-slate-700 rounded-xl cursor-pointer hover:border-pink-500 hover:bg-slate-900/80 transition-all">
                                    <div class="text-center">
                                        <svg class="w-10 h-10 mx-auto mb-2 text-slate-600 group-hover:text-pink-400 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                        </svg>
                                        <p class="text-sm font-medium text-slate-400 group-hover:text-pink-400 transition-colors">
                                            Subir ejemplo en PDF
                                        </p>
                                    </div>
                                </label>

                                {{-- Mostrar archivo seleccionado --}}
                                @if ($documentExample)
                                    <div class="mt-3 p-3 bg-pink-500/10 border border-pink-500/30 rounded-lg flex items-center gap-3">
                                        <svg class="w-5 h-5 text-pink-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        <span class="text-sm text-pink-400 flex-1">{{ $documentExample->getClientOriginalName() }}</span>
                                        <button wire:click="$set('documentExample', null)" class="text-pink-400 hover:text-pink-300">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                            </svg>
                                        </button>
                                    </div>
                                @elseif($editingDocumentId && $period->files->find($editingDocumentId)?->example_name_file)
                                    <div class="mt-3 p-3 bg-slate-700/30 border border-slate-600/50 rounded-lg flex items-center gap-3">
                                        <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                        </svg>
                                        <span class="text-sm text-slate-400">Archivo actual: {{ $period->files->find($editingDocumentId)->example_name_file }}</span>
                                    </div>
                                @endif
                                @error('documentExample')
                                    <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        {{-- Action Buttons --}}
                        <div class="flex justify-end gap-3 mt-8 pt-6 border-t border-slate-700/50">
                            @if($editingDocumentId)
                                <button wire:click="cancelEditDocument"
                                        class="px-6 py-3 bg-slate-700 hover:bg-slate-600 text-white font-medium rounded-xl transition-colors">
                                    Cancelar
                                </button>
                            @endif
                            
                            <button wire:click="{{ $editingDocumentId ? 'saveDocument' : 'createDocument' }}"
                                    wire:loading.attr="disabled"
                                    class="px-6 py-3 bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transition-all disabled:opacity-50 disabled:cursor-not-allowed flex items-center gap-2">
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
                    <div class="bg-slate-800/50 backdrop-blur-xl rounded-2xl border border-slate-700/50 shadow-2xl overflow-hidden">
                        <div class="bg-gradient-to-r from-purple-600/5 to-pink-600/5 p-6 border-b border-slate-700/50">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h2 class="text-xl font-bold text-white flex items-center gap-2">
                                        <svg class="w-5 h-5 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                        </svg>
                                        Documentos Base Creados
                                    </h2>
                                    <p class="text-sm text-slate-400 mt-1">
                                        {{ $period->files->count() }} {{ $period->files->count() === 1 ? 'documento disponible' : 'documentos disponibles' }}
                                    </p>
                                </div>
                                @if($period->files->count() > 0)
                                    <div class="px-4 py-2 bg-purple-500/10 border border-purple-500/30 rounded-lg">
                                        <span class="text-purple-400 font-semibold">{{ $period->files->count() }}</span>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="divide-y divide-slate-700/50">
                            @forelse($period->files as $file)
                                <div class="p-6 hover:bg-slate-700/20 transition-all group">
                                    <div class="flex items-start justify-between gap-4">
                                        <div class="flex-1 min-w-0">
                                            <div class="flex items-start gap-3">
                                                <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-purple-500/20 to-pink-500/20 flex items-center justify-center flex-shrink-0 border border-purple-500/30">
                                                    <svg class="w-5 h-5 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                                    </svg>
                                                </div>
                                                <div class="flex-1 min-w-0">
                                                    <h3 class="text-lg font-semibold text-white truncate">{{ $file->name }}</h3>
                                                    <div class="flex flex-wrap items-center gap-3 mt-2 text-sm text-slate-400">
                                                        <span class="flex items-center gap-1">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                            </svg>
                                                            Subido: {{ $file->created_at->format('d/m/Y') }}
                                                        </span>
                                                        @if($file->limit_date)
                                                            <span class="flex items-center gap-1 px-2 py-1 bg-orange-500/10 border border-orange-500/30 rounded-md text-orange-400">
                                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                                </svg>
                                                                Límite: {{ $file->limit_date }}
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="flex items-center gap-2 flex-shrink-0">
                                            {{-- Vista previa Word --}}
                                            @if($file->file_path)
                                                <button
                                                    wire:click="previewFile('{{ $file->file_path }}', '{{ $file->name_file }}')"
                                                    class="p-2.5 hover:bg-blue-500/20 rounded-lg text-slate-400 hover:text-blue-400 transition-all"
                                                    title="Vista previa Word">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                    </svg>
                                                </button>
                                            @endif

                                            {{-- Vista previa PDF de ejemplo --}}
                                            @if($file->example_path)
                                                <button
                                                    wire:click="previewFile('{{ $file->example_path }}', '{{ $file->example_name_file }}')"
                                                    class="p-2.5 hover:bg-pink-500/20 rounded-lg text-slate-400 hover:text-pink-400 transition-all"
                                                    title="Vista previa PDF ejemplo">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                                    </svg>
                                                </button>
                                            @endif

                                            {{-- Editar --}}
                                            <button
                                                wire:click="editDocument({{ $file->id }})"
                                                class="p-2.5 hover:bg-purple-500/20 rounded-lg text-slate-400 hover:text-purple-400 transition-all"
                                                title="Editar documento">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                </svg>
                                            </button>

                                            {{-- Eliminar --}}
                                            <button
                                                wire:click="deleteDocument({{ $file->id }})"
                                                onclick="return confirm('¿Estás seguro de eliminar este documento?')"
                                                class="p-2.5 hover:bg-red-500/20 rounded-lg text-slate-400 hover:text-red-400 transition-all"
                                                title="Eliminar documento">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="p-16 text-center">
                                    <div class="w-20 h-20 mx-auto mb-4 rounded-2xl bg-slate-700/30 flex items-center justify-center">
                                        <svg class="w-10 h-10 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                        </svg>
                                    </div>
                                    <p class="text-lg font-semibold text-slate-400 mb-2">No hay documentos base</p>
                                    <p class="text-sm text-slate-500">Crea documentos para que los estudiantes puedan subirlos</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                @endif

            </div>
        @endif

        {{-- REVISIÓN DE DOCUMENTOS - VERSIÓN MEJORADA --}}
        @if($activeTab === 'revision')
            <div class="space-y-6">

                {{-- ================== FILTROS MEJORADOS ================== --}}
                <div class="bg-slate-800/50 backdrop-blur-xl rounded-2xl border border-slate-700/50 shadow-2xl p-6">
                    <div class="flex flex-col lg:flex-row gap-4">
                        
                        {{-- Búsqueda --}}
                        <div class="flex-1">
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                    </svg>
                                </div>
                                <input
                                    type="text"
                                    wire:model.live.debounce.300ms="searchRevision"
                                    placeholder="Buscar estudiante..."
                                    class="w-full pl-10 pr-4 py-2.5 bg-slate-900/60 border border-slate-700 rounded-lg text-slate-200 focus:ring-2 focus:ring-purple-500 focus:outline-none"
                                >
                            </div>
                        </div>

                        {{-- Filtro de carrera --}}
                        <select
                            wire:model.live="careerFilter"
                            class="bg-slate-900/60 border border-slate-700 rounded-lg px-4 py-2.5 text-slate-200 focus:ring-2 focus:ring-purple-500 focus:outline-none min-w-[200px]"
                        >
                            <option value="">📚 Todas las carreras</option>
                            @foreach($careers as $career)
                                <option value="{{ $career->id }}">{{ $career->name }}</option>
                            @endforeach
                        </select>

                        {{-- Filtro de estado --}}
                        <select
                            wire:model.live="statusFilter"
                            class="bg-slate-900/60 border border-slate-700 rounded-lg px-4 py-2.5 text-slate-200 focus:ring-2 focus:ring-purple-500 focus:outline-none min-w-[200px]"
                        >
                            <option value="">📋 Todos los estados</option>
                            <option value="pending">⏳ Pendientes de revisar</option>
                            <option value="approved">✅ Aprobados</option>
                            <option value="rejected">❌ Rechazados</option>
                        </select>

                        {{-- Exportar --}}
                        <button
                            wire:click="exportExcel"
                            class="px-5 py-2.5 bg-gradient-to-r from-emerald-600 to-green-600 hover:from-emerald-700 hover:to-green-700 text-white font-semibold rounded-lg shadow-lg hover:shadow-xl transition-all flex items-center gap-2 whitespace-nowrap"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            Excel
                        </button>
                    </div>
                </div>

                {{-- ================== TABLA MEJORADA CON VISTA EXPANDIBLE ================== --}}
                <div class="bg-slate-800/50 backdrop-blur-xl rounded-2xl border border-slate-700/50 shadow-2xl overflow-hidden">
                    
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-slate-900/80 sticky top-0 z-10">
                                <tr>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-slate-300 uppercase w-12"></th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-slate-300 uppercase">Estudiante</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-slate-300 uppercase">Carrera</th>
                                    <th class="px-6 py-4 text-center text-xs font-semibold text-slate-300 uppercase">
                                        <div class="flex items-center justify-center gap-2">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            Aprobados
                                        </div>
                                    </th>
                                    <th class="px-6 py-4 text-center text-xs font-semibold text-slate-300 uppercase">
                                        <div class="flex items-center justify-center gap-2">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            Pendientes
                                        </div>
                                    </th>
                                    <th class="px-6 py-4 text-center text-xs font-semibold text-slate-300 uppercase">
                                        <div class="flex items-center justify-center gap-2">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                            </svg>
                                            Rechazados
                                        </div>
                                    </th>
                                    <th class="px-6 py-4 text-center text-xs font-semibold text-slate-300 uppercase">Progreso</th>
                                    <th class="px-6 py-4 text-center text-xs font-semibold text-slate-300 uppercase">Acciones</th>
                                </tr>
                            </thead>

                            <tbody class="divide-y divide-slate-700/50">
                                @forelse($studentsRevision as $student)
                                    {{-- Fila principal del estudiante --}}
                                    <tr class="hover:bg-slate-700/30 transition group">
                                        
                                        {{-- Botón expandir --}}
                                        <td class="px-6 py-4">
                                            <button
                                                wire:click="toggleStudentExpand({{ $student->id }})"
                                                class="p-1.5 hover:bg-slate-600/50 rounded-lg transition"
                                            >
                                                <svg class="w-5 h-5 text-slate-400 transition-transform {{ $expandedStudent === $student->id ? 'rotate-90' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                                </svg>
                                            </button>
                                        </td>

                                        {{-- Estudiante --}}
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-3">
                                                <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-purple-500 to-pink-600 flex items-center justify-center text-white font-semibold text-sm flex-shrink-0">
                                                    {{ strtoupper(substr($student->name, 0, 1)) }}{{ strtoupper(substr($student->last_name_paterno ?? '', 0, 1)) }}
                                                </div>
                                                <div>
                                                    <p class="text-slate-200 font-medium">
                                                        {{ $student->name }} {{ $student->last_name_paterno }}
                                                    </p>
                                                    <p class="text-xs text-slate-400">{{ $student->control_number }}</p>
                                                </div>
                                            </div>
                                        </td>

                                        {{-- Carrera --}}
                                        <td class="px-6 py-4">
                                            <span class="text-sm text-slate-300">{{ Str::limit($student->career->name ?? 'N/A', 25) }}</span>
                                        </td>

                                        {{-- Aprobados --}}
                                        <td class="px-6 py-4 text-center">
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-emerald-500/20 text-emerald-300 rounded-lg text-sm font-semibold border border-emerald-500/30">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                                </svg>
                                                {{ $student->approved_count ?? 0 }}
                                            </span>
                                        </td>

                                        {{-- Pendientes --}}
                                        <td class="px-6 py-4 text-center">
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-amber-500/20 text-amber-300 rounded-lg text-sm font-semibold border border-amber-500/30">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                </svg>
                                                {{ $student->pending_count ?? 0 }}
                                            </span>
                                        </td>

                                        {{-- Rechazados --}}
                                        <td class="px-6 py-4 text-center">
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-red-500/20 text-red-300 rounded-lg text-sm font-semibold border border-red-500/30">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                                </svg>
                                                {{ $student->rejected_count ?? 0 }}
                                            </span>
                                        </td>

                                        {{-- Progreso --}}
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-3">
                                                @php
                                                    $progress = $student->total > 0 ? round(($student->delivered / $student->total) * 100) : 0;
                                                @endphp
                                                <div class="flex-1 min-w-[100px]">
                                                    <div class="w-full bg-slate-700 rounded-full h-2 overflow-hidden">
                                                        <div
                                                            class="h-2 rounded-full transition-all
                                                                @if($progress >= 80) bg-gradient-to-r from-emerald-500 to-green-400
                                                                @elseif($progress >= 50) bg-gradient-to-r from-yellow-500 to-orange-400
                                                                @else bg-gradient-to-r from-red-500 to-pink-400
                                                                @endif"
                                                            style="width: {{ $progress }}%"
                                                        ></div>
                                                    </div>
                                                </div>
                                                <span class="text-sm text-slate-300 font-semibold min-w-[3rem] text-right">
                                                    {{ $progress }}%
                                                </span>
                                            </div>
                                        </td>

                                        {{-- Acciones --}}
                                        <td class="px-6 py-4">
                                            <div class="flex items-center justify-center gap-2">
                                                <button
                                                    wire:click="exportStudentPDF({{ $student->id }})"
                                                    class="p-2 bg-blue-600/20 hover:bg-blue-600/30 text-blue-300 rounded-lg transition border border-blue-500/30"
                                                    title="Exportar PDF"
                                                >
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                                    </svg>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>

                                    {{-- Fila expandible con documentos --}}
                                    @if($expandedStudent === $student->id)
                                        <tr class="bg-slate-900/50">
                                            <td colspan="8" class="px-6 py-6">
                                                <div class="space-y-4">
                                                    <h4 class="text-sm font-semibold text-slate-300 flex items-center gap-2 mb-4">
                                                        <svg class="w-4 h-4 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                                        </svg>
                                                        Documentos del estudiante
                                                    </h4>

                                                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-3">
                                                        @forelse(
                                                                $student->documents
                                                                    ->filter(fn($d) => $d->file && $d->file->period_id == $periodId)
                                                                    ->sortByDesc(fn($d) => $d->student_file_path)  {{-- Entregados primero --}}
                                                                as $doc
                                                            )
                                                                <div class="group bg-slate-800/50 hover:bg-slate-700/50 border border-slate-700 hover:border-purple-500/50 rounded-lg p-4 transition cursor-pointer"
                                                                    wire:click="quickReviewDocument({{ $doc->id }})">

                                                                    <div class="flex items-start justify-between gap-3">
                                                                        <div class="flex-1 min-w-0">
                                                                            <div class="flex items-center gap-2 mb-2">
                                                                                <svg class="w-5 h-5 text-purple-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                                                                </svg>
                                                                                <h5 class="text-sm font-semibold text-slate-200 truncate">
                                                                                    {{ $doc->file->name ?? $doc->name }}
                                                                                </h5>
                                                                            </div>

                                                                            @if($doc->student_file_path)
                                                                                <p class="text-xs text-slate-400 truncate mb-2">
                                                                                    📎 {{ $doc->student_file_name }}
                                                                                </p>
                                                                            @else
                                                                                <p class="text-xs text-slate-500 mb-2">
                                                                                    ⚠️ Sin entregar
                                                                                </p>
                                                                            @endif

                                                                            @if($doc->limit_date)
                                                                                <p class="text-xs text-slate-500">
                                                                                    📅 Límite: {{ \Carbon\Carbon::parse($doc->limit_date)->format('d/m/Y') }}
                                                                                </p>
                                                                            @endif
                                                                        </div>

                                                                        <div class="flex-shrink-0">
                                                                            @if($doc->student_file_path)
                                                                                @if($doc->status === 'revisado')
                                                                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-emerald-500/20 text-emerald-300 rounded-lg text-xs font-semibold border border-emerald-500/30">
                                                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                                                                        </svg>
                                                                                        Aprobado
                                                                                    </span>
                                                                                @elseif($doc->status === 'rechazado')
                                                                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-red-500/20 text-red-300 rounded-lg text-xs font-semibold border border-red-500/30">
                                                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                                                                        </svg>
                                                                                        Rechazado
                                                                                    </span>
                                                                                @else
                                                                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-amber-500/20 text-amber-300 rounded-lg text-xs font-semibold border border-amber-500/30">
                                                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                                                        </svg>
                                                                                        Revisar
                                                                                    </span>
                                                                                @endif
                                                                            @else
                                                                                <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-slate-700/50 text-slate-500 rounded-lg text-xs font-semibold border border-slate-600/30">
                                                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/>
                                                                                    </svg>
                                                                                    Pendiente
                                                                                </span>
                                                                            @endif
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            @empty
                                                                <div class="col-span-2 text-center py-8 text-slate-500">
                                                                    <svg class="w-12 h-12 mx-auto mb-2 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                                                <svg class="w-16 h-16 text-slate-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                                                </svg>
                                                <p class="text-slate-400 font-semibold mb-1">No se encontraron estudiantes</p>
                                                <p class="text-sm text-slate-500">Intenta con otros filtros de búsqueda</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Paginación --}}
                    @if($studentsRevision->hasPages())
                        <div class="p-4 border-t border-slate-700/50">
                            {{ $studentsRevision->links() }}
                        </div>
                    @endif
                </div>

            </div>
        @endif

        
    </div>

    {{-- ================== MODAL DE VISTA PREVIA GESTIÓN DE ESTUDIANTES================== --}}
    @if($showModal && $selectedStudent)
        <flux:modal wire:model="showModal" class="md:w-3/4 lg:w-2/3">
            <div class="space-y-6">

                {{-- Nombre completo arriba --}}
                <div class="border-b border-slate-700 pb-4">
                    <h2 class="text-2xl font-bold text-white">
                        {{ $selectedStudent->name }}
                        {{ $selectedStudent->last_name_paterno }}
                        {{ $selectedStudent->last_name_materno }}
                    </h2>
                    <p class="text-sm text-slate-400">
                        No. Control: {{ $selectedStudent->control_number }}
                    </p>
                </div>

                {{-- Datos Personales --}}
                <div>
                    <h3 class="text-sm font-semibold text-slate-300 border-b border-slate-700 pb-1 mb-3">
                        Datos Personales
                    </h3>

                    <div class="grid grid-cols-2 gap-4 text-sm text-slate-200">
                        @if($editMode)
                            <div>
                                <label class="text-xs text-slate-400">Nombre</label>
                                <input class="w-full bg-slate-900 border border-slate-700 rounded p-2"
                                    wire:model.defer="studentData.name">
                            </div>

                            <div>
                                <label class="text-xs text-slate-400">Apellido Paterno</label>
                                <input class="w-full bg-slate-900 border border-slate-700 rounded p-2"
                                    wire:model.defer="studentData.last_name_paterno">
                            </div>

                            <div>
                                <label class="text-xs text-slate-400">Apellido Materno</label>
                                <input class="w-full bg-slate-900 border border-slate-700 rounded p-2"
                                    wire:model.defer="studentData.last_name_materno">
                            </div>

                            <div>
                                <label class="text-xs text-slate-400">CURP</label>
                                <input class="w-full bg-slate-900 border border-slate-700 rounded p-2"
                                    wire:model.defer="studentData.curp">
                            </div>

                            <div>
                                <label class="text-xs text-slate-400">RFC</label>
                                <input class="w-full bg-slate-900 border border-slate-700 rounded p-2"
                                    wire:model.defer="studentData.rfc">
                            </div>

                            <div>
                                <label class="text-xs text-slate-400">Teléfono</label>
                                <input class="w-full bg-slate-900 border border-slate-700 rounded p-2"
                                    wire:model.defer="studentData.phone">
                            </div>

                            <div class="col-span-2">
                                <label class="text-xs text-slate-400">Correo Personal</label>
                                <input class="w-full bg-slate-900 border border-slate-700 rounded p-2"
                                    wire:model.defer="studentData.personal_email">
                            </div>
                        @else
                            <p><strong>CURP:</strong> {{ $selectedStudent->curp }}</p>
                            <p><strong>RFC:</strong> {{ $selectedStudent->rfc }}</p>
                            <p><strong>Teléfono:</strong> {{ $selectedStudent->phone }}</p>
                            <p><strong>Correo Personal:</strong> {{ $selectedStudent->personal_email }}</p>
                        @endif
                    </div>
                </div>

                {{-- Datos Académicos --}}
                <div>
                    <h3 class="text-sm font-semibold text-slate-300 border-b border-slate-700 pb-1 mb-3">
                        Datos Académicos
                    </h3>

                    <div class="grid grid-cols-2 gap-4 text-sm text-slate-200">
                        @if($editMode)
                            <div>
                                <label class="text-xs text-slate-400">Correo Institucional</label>
                                <input class="w-full bg-slate-900 border border-slate-700 rounded p-2"
                                    wire:model.defer="studentData.institutional_email">
                            </div>

                            <div>
                                <label class="text-xs text-slate-400">Sistema</label>
                                <select class="w-full bg-slate-900 border border-slate-700 rounded p-2"
                                        wire:model.defer="studentData.system">
                                    <option value="Escolarizado">Escolarizado</option>
                                    <option value="Sabatino">Sabatino</option>
                                </select>
                            </div>

                            <div>
                                <label class="text-xs text-slate-400">Semestre</label>
                                <select class="w-full bg-slate-900 border border-slate-700 rounded p-2"
                                        wire:model.defer="studentData.semester_id">
                                    @foreach($semesters as $semester)
                                        <option value="{{ $semester->id }}">{{ $semester->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="text-xs text-slate-400">Carrera</label>
                                <select class="w-full bg-slate-900 border border-slate-700 rounded p-2"
                                        wire:model.defer="studentData.career_id">
                                    @foreach($careers as $career)
                                        <option value="{{ $career->id }}">{{ $career->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="text-xs text-slate-400">Avance Reticular (%)</label>
                                <input type="number"
                                    class="w-full bg-slate-900 border border-slate-700 rounded p-2"
                                    wire:model.defer="studentData.reticular_progress">
                            </div>
                        @else
                            <p><strong>Correo Institucional:</strong> {{ $selectedStudent->institutional_email }}</p>
                            <p><strong>Sistema:</strong> {{ $selectedStudent->system }}</p>
                            <p><strong>Semestre:</strong> {{ $selectedStudent->semester?->name }}</p>
                            <p><strong>Carrera:</strong> {{ $selectedStudent->career?->name }}</p>
                            <p><strong>Periodo:</strong> {{ $selectedStudent->period?->name }}</p>
                            <p><strong>Avance Reticular:</strong> {{ $selectedStudent->reticular_progress }}%</p>
                        @endif
                    </div>
                </div>

                {{-- Botones --}}
                <div class="flex flex-col sm:flex-row sm:justify-between gap-3 pt-4 border-t border-slate-700">

                    {{-- Izquierda --}}
                    <div class="flex gap-2">
                        <flux:button variant="ghost" wire:click="closeModal">Cerrar</flux:button>

                        @if($editMode)
                            <flux:button variant="ghost" wire:click="cancelEdit">Cancelar</flux:button>
                            <flux:button color="blue" variant="primary" wire:click="updateStudent">
                                Guardar cambios
                            </flux:button>
                        @else
                            <flux:button color="blue" variant="primary"
                                        wire:click="editStudent({{ $selectedStudent->id }})">
                                Editar
                            </flux:button>
                        @endif
                    </div>

                    {{-- Derecha: Aprobar / Rechazar --}}
                    @unless($editMode)
                        <div class="flex gap-2">
                            <flux:button color="green" variant="primary"
                                        wire:click="approve({{ $selectedStudent->id }})">
                                Aprobar
                            </flux:button>

                            <flux:button color="red" variant="primary"
                                        wire:click="reject({{ $selectedStudent->id }})">
                                Rechazar
                            </flux:button>
                        </div>
                    @endunless
                </div>

            </div>
        </flux:modal>
    @endif

    @if($showRejectModal && $selectedStudent)
        <flux:modal wire:model="showRejectModal" class="md:w-1/2 lg:w-1/3">
            <div class="space-y-4">

                <flux:heading size="lg">
                    Motivo de rechazo
                </flux:heading>

                <p class="text-sm text-slate-400">
                    {{ $selectedStudent->name }}
                    {{ $selectedStudent->last_name_paterno }}
                    {{ $selectedStudent->last_name_materno }}
                </p>

                <textarea
                    class="w-full bg-slate-900 border border-slate-700 rounded p-3 text-sm text-slate-200"
                    rows="4"
                    placeholder="Ingresa el motivo del rechazo..."
                    wire:model="rejectionReason"
                ></textarea>

                <div class="flex justify-end gap-2">
                    <flux:button variant="ghost" wire:click="$set('showRejectModal', false)">
                        Cancelar
                    </flux:button>
                    <flux:button color="red" variant="primary" wire:click="confirmReject">
                        Rechazar
                    </flux:button>
                </div>
            </div>
        </flux:modal>
    @endif




    {{-- ================== MODAL DE VISTA PREVIA DOCUMENTOS BASE================== --}}
    @if($previewPath)
        <flux:modal wire:model="previewPath" class="md:w-3/4 lg:w-2/3">
            <div class="flex flex-col h-full">
                @php
                    $ext = strtolower(pathinfo($previewPath, PATHINFO_EXTENSION));
                @endphp

                {{-- Header del Modal --}}
                <div class="flex items-center justify-between p-6 border-b border-slate-700/50 bg-slate-800/50">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-purple-500/20 to-pink-500/20 flex items-center justify-center border border-purple-500/30">
                            @if($ext === 'pdf')
                                <svg class="w-5 h-5 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                </svg>
                            @else
                                <svg class="w-5 h-5 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                            @endif
                        </div>
                        <div>
                            <flux:heading size="lg" class="text-white">Vista previa</flux:heading>
                            <p class="text-sm text-slate-400 mt-0.5 truncate max-w-md">{{ $previewName }}</p>
                        </div>
                    </div>
                    <button 
                        wire:click="$set('previewPath', null)"
                        class="p-2 hover:bg-slate-700 rounded-lg text-slate-400 hover:text-white transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                {{-- Contenido del Modal --}}
                <div class="flex-1 p-6 overflow-hidden">
                    @if($ext === 'pdf')
                        <div class="h-full bg-slate-900/50 rounded-xl overflow-hidden border border-slate-700/50">
                            <iframe 
                                src="{{ asset($previewPath) }}" 
                                class="w-full h-full min-h-[600px]"
                                title="Vista previa PDF">
                            </iframe>
                        </div>
                    @else
                        <div class="flex flex-col items-center justify-center h-full space-y-6 bg-slate-900/30 rounded-xl border-2 border-dashed border-slate-700 p-12">
                            <div class="w-20 h-20 rounded-full bg-slate-800/50 flex items-center justify-center">
                                <svg class="w-10 h-10 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                            </div>
                            <div class="text-center space-y-2">
                                <p class="text-lg font-medium text-slate-400">
                                    No se puede previsualizar este tipo de archivo
                                </p>
                                <p class="text-sm text-slate-500">
                                    Descarga el archivo para verlo en tu dispositivo
                                </p>
                            </div>
                            <a 
                                href="{{ asset($previewPath) }}" 
                                target="_blank"
                                class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 text-white font-medium rounded-xl shadow-lg hover:shadow-xl transition-all">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                </svg>
                                Descargar {{ $previewName }}
                            </a>
                        </div>
                    @endif
                </div>

                {{-- Footer del Modal --}}
                <div class="flex justify-end gap-3 p-6 border-t border-slate-700/50 bg-slate-800/50">
                    @if($ext === 'pdf')
                        <a 
                            href="{{ asset($previewPath) }}" 
                            download
                            class="px-4 py-2 bg-slate-700 hover:bg-slate-600 text-white rounded-lg transition-colors flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                            </svg>
                            Descargar
                        </a>
                    @endif
                    <flux:button variant="ghost" wire:click="$set('previewPath', null)">
                        Cerrar
                    </flux:button>
                </div>
            </div>
        </flux:modal>
    @endif




    {{-- ================== MODAL RÁPIDO DE REVISIÓN  ================== --}}
    @if($quickReviewDoc)
        <div class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex items-center justify-center min-h-screen px-4">
                
                <div class="fixed inset-0 bg-slate-900/90 backdrop-blur-sm" wire:click="closeQuickReview"></div>

                <div class="relative inline-block w-full max-w-6xl bg-slate-800 rounded-2xl border border-slate-700 shadow-2xl">
                    
                    {{-- Header compacto --}}
                    <div class="bg-gradient-to-r from-purple-600/20 to-pink-600/20 border-b border-slate-700 px-6 py-4">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-purple-500 to-pink-600 flex items-center justify-center">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-bold text-white">{{ $quickReviewDoc->file->name ?? $quickReviewDoc->name }}</h3>
                                    <p class="text-xs text-slate-400">{{ $quickReviewDoc->student->name }} {{ $quickReviewDoc->student->last_name_paterno }}</p>
                                </div>
                            </div>
                            
                            <div class="flex items-center gap-3">
                                @if($quickReviewDoc->status)
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold border
                                        @if($quickReviewDoc->status === 'revisado') bg-emerald-500/20 text-emerald-300 border-emerald-500/30
                                        @elseif($quickReviewDoc->status === 'rechazado') bg-red-500/20 text-red-300 border-red-500/30
                                        @else bg-amber-500/20 text-amber-300 border-amber-500/30
                                        @endif">
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
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            En revisión
                                        @endif
                                    </span>
                                @endif
                                
                                <button wire:click="closeQuickReview" class="text-slate-400 hover:text-white transition">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>

                    {{-- Layout de 2 columnas --}}
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 p-6">
                        
                        {{-- Columna izquierda: Preview --}}
                        <div class="lg:col-span-2 space-y-4">
                            <div class="bg-slate-900/30 rounded-xl p-4 border border-slate-700/50">
                                @if($quickReviewPreviewUrl)
                                    @if(pathinfo($quickReviewDoc->student_file_path, PATHINFO_EXTENSION) === 'pdf')
                                        <div class="bg-white rounded-lg overflow-hidden">
                                            <iframe src="{{ $quickReviewPreviewUrl }}" class="w-full h-[600px]"></iframe>
                                        </div>
                                    @else
                                        <div class="flex flex-col items-center justify-center py-20 border-2 border-dashed border-slate-700 rounded-lg">
                                            <svg class="w-16 h-16 text-slate-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                            </svg>
                                            <p class="text-slate-400 mb-2">Vista previa no disponible</p>
                                            <a href="{{ $quickReviewPreviewUrl }}" download class="text-sm text-purple-400 hover:text-purple-300">
                                                Descargar para ver
                                            </a>
                                        </div>
                                    @endif
                                @else
                                    <div class="flex flex-col items-center justify-center py-20">
                                        <svg class="w-16 h-16 text-slate-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                        </svg>
                                        <p class="text-slate-500">No se ha cargado ningún documento</p>
                                    </div>
                                @endif
                            </div>

                            {{-- Botón de descarga --}}
                            @if($quickReviewPreviewUrl)
                                <a href="{{ $quickReviewPreviewUrl }}" download="{{ $quickReviewDoc->student_file_name }}"
                                class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600/20 hover:bg-blue-600/30 text-blue-300 rounded-lg transition border border-blue-500/30">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                    </svg>
                                    Descargar documento
                                </a>
                            @endif
                        </div>

                        {{-- Columna derecha: Acciones --}}
                        <div class="space-y-4">
                            
                            {{-- Info del documento con fecha editable --}}
                            <div class="bg-slate-900/50 rounded-xl p-4 border border-slate-700/50 space-y-3">
                                <h4 class="text-sm font-semibold text-slate-300 flex items-center gap-2">
                                    <svg class="w-4 h-4 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    Información
                                </h4>
                                
                                <div class="space-y-2 text-sm">
                                    <div class="flex items-start gap-2">
                                        <span class="text-slate-500 min-w-[80px]">Archivo:</span>
                                        <span class="text-slate-300 truncate">{{ $quickReviewDoc->student_file_name ?? 'No entregado' }}</span>
                                    </div>

                                    {{-- Fecha límite --}}
                                    @if($quickReviewDoc->limit_date)
                                        <div class="flex items-center gap-2">
                                            <span class="text-slate-500 min-w-[80px]">Límite:</span>
                                            <input 
                                                type="date"
                                                wire:model.defer="editingDates.{{ $quickReviewDoc->id }}"
                                                wire:change="updateDate({{ $quickReviewDoc->id }})"
                                                class="px-2 py-1 rounded border border-slate-600 text-slate-200 bg-slate-900/50 text-sm"
                                                min="{{ \Carbon\Carbon::parse($quickReviewDoc->limit_date)->format('Y-m-d') }}"
                                            >
                                        </div>
                                    @endif

                                    {{-- Fecha de entrega --}}
                                    @if($quickReviewDoc->uploaded_at)
                                        <div class="flex items-center gap-2">
                                            <span class="text-slate-500 min-w-[80px]">Entregado:</span>
                                            <span class="text-slate-300">{{ \Carbon\Carbon::parse($quickReviewDoc->uploaded_at)->format('d/m/Y H:i') }}</span>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            {{-- Comentarios --}}
                            <div class="bg-slate-900/50 rounded-xl p-4 border border-slate-700/50">
                                <label class="block text-sm font-semibold text-slate-300 mb-3 flex items-center gap-2">
                                    <svg class="w-4 h-4 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/>
                                    </svg>
                                    Observaciones
                                </label>
                                <textarea 
                                    wire:model.defer="quickReviewComments"
                                    rows="6"
                                    placeholder="Escribe observaciones o comentarios..."
                                    class="w-full px-3 py-2 bg-slate-900/60 border border-slate-700 rounded-lg text-slate-200 text-sm placeholder-slate-500 focus:ring-2 focus:ring-purple-500 focus:outline-none resize-none"
                                ></textarea>
                            </div>

                            {{-- Botones de acción --}}
                            <div class="space-y-2">
                                <button 
                                    wire:click="quickApproveDocument"
                                    class="w-full inline-flex items-center justify-center gap-2 px-4 py-3 bg-gradient-to-r from-emerald-600 to-green-600 hover:from-emerald-700 hover:to-green-700 text-white rounded-lg transition font-semibold shadow-lg"
                                >
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    Aprobar documento
                                </button>

                                <button 
                                    wire:click="quickRejectDocument"
                                    class="w-full inline-flex items-center justify-center gap-2 px-4 py-3 bg-gradient-to-r from-red-600 to-pink-600 hover:from-red-700 hover:to-pink-700 text-white rounded-lg transition font-semibold shadow-lg"
                                >
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                    Rechazar documento
                                </button>

                                <button 
                                    wire:click="closeQuickReview"
                                    class="w-full px-4 py-3 bg-slate-700 hover:bg-slate-600 text-white rounded-lg transition font-medium"
                                >
                                    Cerrar
                                </button>
                            </div>

                            {{-- Navegación entre documentos --}}
                            @if($nextPendingDoc || $previousPendingDoc)
                                <div class="flex gap-2 pt-2 border-t border-slate-700">
                                    <button 
                                        wire:click="navigateToPreviousDoc"
                                        @if(!$previousPendingDoc) disabled @endif
                                        class="flex-1 px-3 py-2 bg-slate-700 hover:bg-slate-600 disabled:opacity-50 disabled:cursor-not-allowed text-white rounded-lg transition text-sm"
                                    >
                                        ← Anterior
                                    </button>
                                    <button 
                                        wire:click="navigateToNextDoc"
                                        @if(!$nextPendingDoc) disabled @endif
                                        class="flex-1 px-3 py-2 bg-slate-700 hover:bg-slate-600 disabled:opacity-50 disabled:cursor-not-allowed text-white rounded-lg transition text-sm"
                                    >
                                        Siguiente →
                                    </button>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif




    




</div>