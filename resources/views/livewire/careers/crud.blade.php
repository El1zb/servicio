<div class="space-y-8 min-h-screen p-6">

    {{-- Header --}}
    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-6 rounded-xl shadow-sm p-6" 
         style="background-color: var(--student-document-bg);">
        <div>
            <x-auth-header
                title="Carreras"
                description="Administración y gestión de carreras académicas."
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
                bg-[var(--modal-btn-document-descargar)]!
                text-[var(--modal-btn-document-descargar-text)]!
                shadow-lg shadow-[var(--modal-btn-document-descargar-shadow)]
                hover:bg-[var(--modal-btn-document-descargar-hover)]!
                hover:shadow-xl hover:-translate-y-0.5
                transition-all duration-300
                disabled:opacity-60 disabled:cursor-not-allowed
                gap-2
            ">
            Nueva Carrera
        </flux:button>
    </div>

    {{-- Mensaje de sesión --}}
    @if (session()->has('message'))
        <div class="rounded-xl p-4 shadow-lg border flex items-center gap-3"
             style="background-color: var(--student-document-bg-content-status-approved); 
                    border-color: var(--student-document-text-content-status-approved-icon);">
            <div class="flex-shrink-0">
                <svg class="w-5 h-5" style="color: var(--student-document-text-content-status-approved-icon);" 
                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <p class="text-sm font-medium" style="color: var(--student-document-text-content-status-approved-icon);">
                {{ session('message') }}
            </p>
        </div>
    @endif

    {{-- Buscador y tabla --}}
    <div class="rounded-xl p-5 shadow-lg" style="background-color: var(--student-document-bg);">

        <div class="rounded-xl p-5">
            <div class="relative max-w-2xl">
                {{-- Ícono de búsqueda --}}
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        style="color: var(--student-document-text-primary);">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>

                {{-- Input --}}
                <input 
                    type="text"
                    placeholder="Buscar carreras..."
                    wire:model.live="search"
                    class="w-full pl-10 pr-4 py-2.5 rounded-lg text-sm transition-all"
                    style="
                        background-color: var(--student-document-bg-card);
                        color: var(--student-document-text-primary);
                        border: 1px solid var(--student-document-border-content);
                    "
                />
            </div>
        </div>


        @if($careers->count())
            <div class="space-y-3 rounded-xl p-4">
                @foreach($careers as $career)
                    <div class="group relative rounded-xl p-4 transition-all duration-200 hover:shadow-lg border"
                        style="background-color: var(--student-document-bg-card); 
                               border-color: var(--student-document-border-content);">

                        <div class="flex items-center gap-4">
                            {{-- Nombre --}}
                            <div class="flex-1 min-w-0">
                                <h3 class="text-base font-bold truncate" 
                                    style="color: var(--student-document-text-primary);">
                                    {{ $career->name }}
                                </h3>
                            </div>

                            {{-- Acciones --}}
                            <div class="flex items-center gap-2 opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                                <button 
                                    wire:click="edit({{ $career->id }})"
                                    class="inline-flex items-center justify-center w-9 h-9 rounded-lg transition-all hover:scale-110"
                                    style="color: var(--student-document-text-primary); 
                                        background-color: var(--student-document-bg-content); 
                                        border: 1px solid var(--student-document-border-content);"
                                    title="Editar carrera">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </button>

                                <button 
                                    wire:click="confirmDelete({{ $career->id }})"
                                    class="inline-flex items-center justify-center w-9 h-9 rounded-lg transition-all hover:scale-110"
                                    style="color: var(--student-document-text-content-status-rejected-icon); 
                                        background-color: var(--student-document-bg-content-status-rejected); 
                                        border: 1px solid var(--status-border-rejected);"
                                    title="Eliminar carrera">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Paginación --}}
            <div class="mt-6">
                {{ $careers->links() }}
            </div>

        @else
            <div class="text-center py-20 rounded-2xl border border-dashed"
                 style="background-color: var(--student-document-bg-card); 
                        border-color: var(--student-document-border-content);">
                <h3 class="text-lg font-bold mb-2" style="color: var(--student-document-text-primary);">
                    {{ $search ? 'Sin resultados' : 'No hay carreras registradas' }}
                </h3>
                <p class="mb-6 max-w-sm mx-auto text-sm" style="color: var(--student-document-text-secondary);">
                    {{ $search ? 'No se encontraron carreras con esos términos.' : 'Crea la primera carrera para comenzar.' }}
                </p>
                @if(!$search)
                    <flux:button variant="primary" wire:click="create" icon="plus">
                        Crear Carrera
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
        class="w-[95vw] sm:w-[450px] max-w-[95vw]">
        <div class="flex flex-col max-h-[85vh]">

                {{-- Header --}}
                <div 
                class="relative px-6 py-5 flex items-center gap-4 overflow-hidden flex-shrink-0 rounded-t-xl border-b"
                style="
                    background: linear-gradient(
                        135deg,
                        var(--student-document-bg-card-header) 0%,
                        var(--student-document-bg-card-header-2) 100%
                    );
                    border-bottom: 1px solid var(--student-document-border-content);
                ">
                    <div class="flex-1 min-w-0">
                        <h3 class="text-lg font-bold" style="color: var(--student-document-text-primary);">
                            {{ $careerId ? 'Editar Carrera' : 'Nueva Carrera' }}
                        </h3>
                        <p class="text-sm truncate" style="color: var(--student-document-text-secondary);">
                            {{ $careerId ? 'Modifique los datos de la carrera' : 'Complete la información requerida' }}
                        </p>
                    </div>
                </div>

                {{-- Contenido --}}
                <div class="flex-1 overflow-y-auto p-6" style="background-color: var(--student-document-bg-card);">
                    <div class="p-4 rounded-xl" style="background-color: var(--student-document-bg);">
                        <label class="block text-sm font-semibold mb-2" style="color: var(--student-document-text-primary);">
                            Nombre de la Carrera 
                            <span style="color: var(--student-document-text-content-status-rejected-icon);">*</span>
                        </label>

                        <flux:input 
                            wire:model="name" 
                            type="text" 
                            placeholder="Ejemplo: Ingeniería en Sistemas"
                            class="w-full"
                            style="background-color: var(--student-document-bg-card); 
                                border-color: var(--student-document-border-content); 
                                color: var(--modal-text-primary);" 
                        />

                        {{-- Aquí va el error --}}
                        @error('name')
                            <p class="mt-2 text-sm flex items-center gap-2" style="color: #ee6e6c;">
                                <!-- Icono de alerta -->
                                <svg class="w-5 h-5 flex-shrink-0" viewBox="0 0 20 20" fill="#ee6e6c">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.72-1.36 3.485 0l6.518 11.59c.75 1.335-.213 2.971-1.742 2.971H3.48c-1.529 0-2.492-1.636-1.742-2.971L8.257 3.1zM11 13a1 1 0 10-2 0 1 1 0 002 0zm-1-8a1 1 0 00-.993.883L9 6v4a1 1 0 001.993.117L11 10V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>
                </div>


                {{-- Footer --}}
                <div class="px-6 py-4 flex flex-col-reverse sm:flex-row items-center justify-end gap-3 border-t flex-shrink-0 rounded-b-xl"
                     style="background-color: var(--student-document-bg-card); border-color: var(--student-document-border-content);">
                    <flux:modal.close>
                        <button wire:click="closeModal"
                                class="w-full sm:w-auto px-5 py-2.5 rounded-lg font-medium transition-all duration-200"
                                style="
                            background-color: var(--modal-btn-close);
                            color: var(--modal-btn-close-text);
                        "
                        onmouseover="this.style.transform='translateY(-2px)'; this.style.filter='brightness(1.05)';"
                        onmouseout="this.style.transform='translateY(0)'; this.style.filter='brightness(1)';">
                            Cancelar
                        </button>
                    </flux:modal.close>
                    <button wire:click="save"
                            class="w-full sm:w-auto px-6 py-2.5 rounded-lg font-semibold transition-all duration-200 shadow-lg text-white"
                            style="background: linear-gradient(135deg, var(--color-hero-accent-primary) 0%, var(--color-hero-accent-secondary) 100%);"
                    onmouseover="this.style.transform='translateY(-2px)'; this.style.filter='brightness(1.05)';"
                    onmouseout="this.style.transform='translateY(0)'; this.style.filter='brightness(1)';">
                        {{ $careerId ? 'Guardar Cambios' : 'Crear Carrera' }}
                    </button>
                </div>

            </div>
        </flux:modal>
    @endif

    {{-- Modal de confirmación de eliminación --}}
    @if($isDeleteModalOpen && $careerToDelete)
        <flux:modal wire:model="isDeleteModalOpen" :dismissible="false" class="md:w-96">
            <div class="flex flex-col max-h-[85vh]">

                {{-- Contenido --}}
                <div class="flex-1 overflow-y-auto p-6" style="background-color: var(--student-document-bg-card);">
                    <div class="space-y-4">
                        <h3 class="text-lg font-bold" style="color: var(--student-document-text-primary);">
                            Confirmar Eliminación
                        </h3>
                        <p class="text-sm truncate" style="color: var(--student-document-text-secondary);">
                            {{ optional(App\Models\Career::find($careerToDelete))->name }}
                        </p>
                    </div>
                </div>

                {{-- Footer --}}
                <div class="px-6 py-4 flex flex-col-reverse sm:flex-row items-center justify-end gap-3 border-t flex-shrink-0 rounded-b-xl"
                     style="background-color: var(--student-document-bg-card); border-color: var(--student-document-border-content);">
                    <button wire:click="$set('isDeleteModalOpen', false)"
                            class="w-full sm:w-auto px-5 py-2.5 rounded-lg font-medium transition-all duration-200"
                            style="background-color: var(--modal-btn-close); color: var(--modal-btn-close-text);">
                        Cancelar
                    </button>
                    <button wire:click="deleteCareer"
                            class="w-full sm:w-auto px-6 py-2.5 rounded-lg font-semibold transition-all duration-200 shadow-lg"
                            style="background-color: var(--modal-btn-reject); color: var(--modal-btn-reject-text);">
                        Eliminar
                    </button>
                </div>

            </div>
        </flux:modal>
    @endif

</div>
