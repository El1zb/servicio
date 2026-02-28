<div class="space-y-8 min-h-screen p-6">

    {{-- Header --}}
    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-6 rounded-xl shadow-sm p-6" 
         style="background-color: var(--index-bg);">
        <div>
            <x-auth-header
                title="Semestres"
                description="Administración y gestión de semestres académicos." 
                :center="false"
            />
        </div>
        <flux:button 
            variant="primary" 
            wire:click="create" 
            icon="plus"
            class="
                group relative
                inline-flex items-center justify-center
                px-4 py-2
                rounded-[var(--radius-md)]
                bg-[var(--index-btn-primary-bg)]!
                text-[var(--index-btn-primary-text)]!
                shadow-lg shadow-[var(--index-btn-primary-shadow)]
                hover:bg-[var(--index-btn-primary-hover)]!
                hover:shadow-xl hover:-translate-y-0.5
                transition-all duration-300
                disabled:opacity-60 disabled:cursor-not-allowed
                gap-2
            ">
            Nuevo Semestre
        </flux:button>
    </div>

    {{-- Mensaje de sesión --}}
    @if (session()->has('message'))
        <div class="rounded-xl p-4 shadow-lg border flex items-center gap-3"
             style="background-color: var(--index-status-approved-bg); 
                    border-color: var(--index-status-approved-icon);">
            <div class="flex-shrink-0">
                <svg class="w-5 h-5" style="color: var(--index-status-approved-icon);" 
                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <p class="text-sm font-medium" style="color: var(--index-status-approved-icon);">
                {{ session('message') }}
            </p>
        </div>
    @endif


    <div class="rounded-xl p-5 shadow-lg" style="background-color: var(--index-bg);">
    
        {{-- Buscador --}}
        <div class="rounded-xl p-5">
            <div class="relative max-w-2xl">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        style="color: var(--index-text-primary);">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>

                <input 
                    type="text"
                    placeholder="Buscar semestres..."
                    wire:model.live="search"
                    class="w-full pl-10 pr-4 py-2.5 rounded-lg text-sm transition-all"
                    style="
                        background-color: var(--index-card-bg);
                        color: var(--index-text-primary);
                        border: 1px solid var(--index-border);
                    "
                />
            </div>
        </div>

        {{-- Grid de Semestres --}}
        @if($semesters->count())
            <div class="grid grid-cols-1 sm:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-3 p-4">

                @foreach($semesters as $semester)
                    <div style="background-color: var(--index-card-bg); border: 1px solid var(--index-border); border-radius: 14px; overflow: hidden; transition: border-color 0.18s ease, box-shadow 0.18s ease; display: flex; flex-direction: column; height: 100%;"
                 onmouseover="this.style.borderColor='rgba(255,255,255,0.1)'; this.style.boxShadow='0 4px 24px rgba(0,0,0,0.25)'"
                 onmouseout="this.style.borderColor='var(--index-border)'; this.style.boxShadow='none'">

                        {{-- Cuerpo --}}
                        <div style="padding: 20px 18px 16px; flex: 1; display: flex; flex-direction: column; gap: 14px;">

                            {{-- Nombre --}}
                            <div>
                                <p style="font-size: 9px; font-weight: 600; letter-spacing: 0.1em; text-transform: uppercase; color: var(--index-text-secondary); margin-bottom: 4px; margin-top: 0;">
                                    Semestre
                                </p>
                                <h3 style="font-size: 13.5px; font-weight: 500; color: var(--index-text-primary); line-height: 1.35; letter-spacing: -0.01em; margin: 0;">
                                    {{ $semester->name }}
                                </h3>
                            </div>

                        </div>

                        {{-- Footer --}}
                        <div style="
                                padding: 8px 10px;
                                border-top: 1px solid var(--index-border);
                                display: flex;
                                align-items: center;
                                justify-content: flex-end;
                                gap: 2px;
                                flex-shrink: 0;
                            ">

                            {{-- Editar --}}
                            <div class="relative group/edit">
                                <button
                                    wire:click="edit({{ $semester->id }})"
                                    style="
                                        width: 28px; height: 28px;
                                        display: flex; align-items: center; justify-content: center;
                                        border-radius: 6px;
                                        border: 1px solid transparent;
                                        background: transparent;
                                        color: var(--index-text-secondary);
                                        cursor: pointer;
                                        transition: color 0.15s, border-color 0.15s, background-color 0.15s;
                                    "
                                    onmouseover="this.style.color='var(--index-text-primary)'; this.style.borderColor='var(--period-detail-btn-edit-hover)'"
                                    onmouseout="this.style.color='var(--index-text-secondary)'; this.style.borderColor='transparent'">
                                    <svg width="11" height="11" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </button>
                            </div>

                            {{-- Separador --}}
                            <span style="width: 1px; height: 14px; background-color: var(--index-border); display: inline-block; margin: 0 2px; flex-shrink: 0;"></span>

                            {{-- Eliminar --}}
                            <div class="relative group/delete">
                                <button
                                    wire:click="confirmDelete({{ $semester->id }})"
                                    style="
                                        width: 28px; height: 28px;
                                        display: flex; align-items: center; justify-content: center;
                                        border-radius: 6px;
                                        border: 1px solid transparent;
                                        background: transparent;
                                        color: var(--index-text-secondary);
                                        cursor: pointer;
                                        transition: color 0.15s, border-color 0.15s, background-color 0.15s;
                                    "
                                    onmouseover="this.style.color='var(--index-status-rejected-icon)'; this.style.borderColor='var(--index-status-rejected-border)'"
                                    onmouseout="this.style.color='var(--index-text-secondary)'; this.style.borderColor='transparent'">
                                    <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                            </div>

                        </div>
                    </div>
                @endforeach

            </div>

            {{-- Paginación --}}
            <div class="mt-6 px-4">
                {{ $semesters->links() }}
            </div>

        @else
            {{-- Estado vacío --}}
            <div style="text-align: center; padding: 72px 20px; margin: 16px; border-radius: 16px; border: 1px dashed var(--index-border); background-color: var(--index-card-bg);">

                <div style="width: 48px; height: 48px; border-radius: 12px; background-color: var(--index-icon-bg); display: flex; align-items: center; justify-content: justify-center; margin: 0 auto 14px;">
                    <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        style="color: var(--index-icon-text); opacity: 0.6;">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                </div>

                <h3 style="font-size: 14px; font-weight: 500; color: var(--index-text-primary); margin-bottom: 6px;">
                    {{ $search ? 'Sin resultados' : 'No hay semestres registrados' }}
                </h3>
                <p style="font-size: 12px; font-weight: 300; color: var(--index-text-secondary); margin-bottom: 20px; max-width: 260px; margin-left: auto; margin-right: auto; line-height: 1.5;">
                    {{ $search ? 'Intenta con otros términos de búsqueda.' : 'Crea el primer semestre para comenzar.' }}
                </p>

                @if(!$search)
                    <flux:button variant="primary" wire:click="create" icon="plus">
                        Crear Semestre
                    </flux:button>
                @endif
            </div>
        @endif
    </div>

    {{-- Modal creación/edición --}}
    @if($isOpen)
    <flux:modal 
        wire:model="isOpen" 
        :dismissible="false"
        class="w-[95vw] sm:w-[90vw] md:w-[500px] max-w-[95vw]">

        <div class="flex flex-col" style="max-height: 88vh;">

            {{-- ── Header ── --}}
            <div class="flex items-center justify-between px-4 sm:px-6 py-3 sm:py-4 flex-shrink-0"
                style="border-bottom: 1px solid var(--period-detail-border); background-color: var(--period-detail-card-bg);">

                <div class="flex items-center gap-3 min-w-0 flex-1">
                    <div class="min-w-0 flex-1">
                        <h3 class="text-sm font-semibold leading-tight truncate"
                            style="color: var(--period-detail-text-primary);">
                            {{ $semesterId ? 'Editar Semestre' : 'Nuevo Semestre' }}
                        </h3>
                        <p class="text-xs mt-0.5 truncate" style="color: var(--period-detail-text-secondary);">
                            {{ $semesterId ? 'Modifique los datos del semestre' : 'Complete la información requerida' }}
                        </p>
                    </div>
                </div>
            </div>

            {{-- ── Contenido ── --}}
            <div class="flex flex-col gap-3 p-3 sm:p-4 overflow-y-auto flex-1"
                style="background-color: var(--period-detail-bg);">

                {{-- Nombre del Semestre --}}
                <div class="p-3 rounded-lg border"
                    style="background-color: var(--period-detail-card-bg); border-color: var(--period-detail-border);">

                    <p class="text-xs font-semibold mb-2 flex items-center gap-1.5"
                        style="color: var(--period-detail-text-primary);">
                        <svg class="w-3.5 h-3.5 flex-shrink-0" style="color: var(--period-detail-accent);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                        </svg>
                        Nombre del Semestre
                        <span style="color: rgba(239,68,68,0.8);">*</span>
                    </p>

                    <input type="text"
                        wire:model="name"
                        placeholder="Ejemplo: Semestre 1"
                        class="w-full px-3 py-2 rounded-lg border text-xs transition-all focus:outline-none focus:ring-1"
                        style="background-color: var(--period-detail-bg);
                            border-color: var(--period-detail-border);
                            color: var(--period-detail-text-primary);
                            --tw-ring-color: var(--period-detail-accent);">

                    @error('name')
                        <p class="mt-1.5 text-xs flex items-center gap-1.5" style="color: #ee6e6c;">
                            <svg class="w-3.5 h-3.5 flex-shrink-0" viewBox="0 0 20 20" fill="#ee6e6c">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.72-1.36 3.485 0l6.518 11.59c.75 1.335-.213 2.971-1.742 2.971H3.48c-1.529 0-2.492-1.636-1.742-2.971L8.257 3.1zM11 13a1 1 0 10-2 0 1 1 0 002 0zm-1-8a1 1 0 00-.993.883L9 6v4a1 1 0 001.993.117L11 10V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

            </div>

            {{-- ── Footer ── --}}
            <div class="flex-shrink-0 flex items-center justify-end gap-2 px-3 sm:px-4 py-3"
                style="border-top: 1px solid var(--period-detail-border); background-color: var(--period-detail-card-bg);">

                <flux:modal.close>
                    <button
                        wire:click="closeModal"
                        class="px-4 py-2 text-xs font-medium rounded-lg transition-all duration-200 hover:-translate-y-0.5"
                        style="color: var(--period-detail-text-secondary); background-color: transparent;"
                        onmouseover="this.style.color='var(--period-detail-text-primary)'; this.style.backgroundColor='var(--period-detail-border)'"
                        onmouseout="this.style.color='var(--period-detail-text-secondary)'; this.style.backgroundColor='transparent'">
                        Cancelar
                    </button>
                </flux:modal.close>

                <button wire:click="save" wire:loading.attr="disabled"
                    class="px-4 py-2.5 text-xs font-semibold rounded-lg border border-transparent shadow-sm transition-all duration-200 hover:-translate-y-0.5 hover:shadow-lg disabled:opacity-50 flex items-center gap-2"
                    style="background-color: var(--index-btn-primary-bg); color: var(--index-btn-primary-text);"
                    onmouseover="this.style.opacity='0.85'"
                    onmouseout="this.style.opacity='1'">
                    <span wire:loading.remove wire:target="save">
                        {{ $semesterId ? 'Guardar Cambios' : 'Crear Semestre' }}
                    </span>
                    <span wire:loading wire:target="save" class="flex items-center gap-2">
                        <svg class="animate-spin h-3.5 w-3.5" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </span>
                </button>

            </div>

        </div>
    </flux:modal>
    @endif

    {{-- Modal de confirmación de eliminación --}}
    @if($isDeleteModalOpen && $semesterToDelete)
    <flux:modal 
        wire:model="isDeleteModalOpen" 
        :dismissible="false"
        class="w-[95vw] sm:w-[420px] max-w-[95vw]">

        <div class="flex flex-col">

            {{-- ── Header ── --}}
            <div class="flex items-center justify-between px-4 sm:px-6 py-3 sm:py-4 flex-shrink-0"
                style="border-bottom: 1px solid var(--period-detail-border); background-color: var(--period-detail-card-bg);">
                <div class="min-w-0 flex-1">
                    <h3 class="text-sm font-semibold leading-tight truncate"
                        style="color: var(--period-detail-text-primary);">
                        Confirmar Eliminación
                    </h3>
                    <p class="text-xs mt-0.5 truncate" style="color: var(--period-detail-text-secondary);">
                        {{ optional(App\Models\Semester::find($semesterToDelete))->name }}
                    </p>
                </div>
            </div>

            {{-- ── Body ── --}}
            <div class="px-4 sm:px-6 py-5 sm:py-6"
                style="background-color: var(--period-detail-card-bg);">

                <div class="p-3 sm:p-4 rounded-lg sm:rounded-xl border"
                    style="background-color: var(--period-detail-bg); border-color: var(--period-detail-border);">
                    <div class="flex items-start gap-3">
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium mb-1" style="color: var(--period-detail-text-primary);">
                                ¿Está seguro de que desea eliminar este semestre?
                            </p>
                            <p class="text-xs" style="color: var(--period-detail-text-secondary);">
                                Esta acción es irreversible y no podrá recuperarse.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ── Footer ── --}}
            <div class="flex items-center justify-end gap-2 px-4 sm:px-6 py-3 sm:py-4 flex-shrink-0"
                style="border-top: 1px solid var(--period-detail-border); background-color: var(--period-detail-card-bg);">

                <button wire:click="$set('isDeleteModalOpen', false)"
                    class="px-4 py-2 rounded-lg text-xs font-medium transition-all duration-200 hover:-translate-y-0.5"
                    style="color: var(--period-detail-text-secondary); background-color: transparent;"
                    onmouseover="this.style.color='var(--period-detail-text-primary)'; this.style.backgroundColor='var(--period-detail-border)'"
                    onmouseout="this.style.color='var(--period-detail-text-secondary)'; this.style.backgroundColor='transparent'">
                    Cancelar
                </button>

                <button wire:click="deleteSemester"
                    class="px-5 py-2 rounded-lg text-xs font-semibold transition-all duration-200 hover:-translate-y-0.5 hover:shadow-lg flex items-center gap-2"
                    style="background-color: var(--period-detail-btn-reject-bg); color: var(--period-detail-btn-reject-text);"
                    onmouseover="this.style.opacity='0.85'"
                    onmouseout="this.style.opacity='1'">
                    <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                    Eliminar
                </button>
            </div>

        </div>
    </flux:modal>
    @endif


</div>