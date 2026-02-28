{{-- Modal: Crear / Editar Carrera --}}
@if($isOpen)
<flux:modal wire:model="isOpen" :dismissible="false"
            class="w-[95vw] sm:w-[90vw] md:w-[500px] max-w-[95vw]">

    <div class="flex flex-col" style="max-height: 88vh;">

        {{-- Header --}}
        <div class="flex items-center justify-between px-4 sm:px-6 py-3 sm:py-4 flex-shrink-0"
             style="border-bottom: 1px solid var(--period-detail-border); background-color: var(--period-detail-card-bg);">
            <div class="min-w-0 flex-1">
                <h3 class="text-sm font-semibold leading-tight truncate"
                    style="color: var(--period-detail-text-primary);">
                    {{ $careerId ? 'Editar Carrera' : 'Nueva Carrera' }}
                </h3>
                <p class="text-xs mt-0.5 truncate" style="color: var(--period-detail-text-secondary);">
                    {{ $careerId ? 'Modifique los datos de la carrera' : 'Complete la información requerida' }}
                </p>
            </div>
        </div>

        {{-- Contenido --}}
        <div class="flex flex-col gap-3 p-3 sm:p-4 overflow-y-auto flex-1"
             style="background-color: var(--period-detail-bg);">

            <div class="p-3 rounded-lg border"
                 style="background-color: var(--period-detail-card-bg); border-color: var(--period-detail-border);">

                <p class="text-xs font-semibold mb-2 flex items-center gap-1.5"
                   style="color: var(--period-detail-text-primary);">
                    <svg class="w-3.5 h-3.5 flex-shrink-0" style="color: var(--period-detail-accent);"
                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/>
                    </svg>
                    Nombre de la Carrera
                    <span style="color: rgba(239,68,68,0.8);">*</span>
                </p>

                <input type="text"
                       wire:model="name"
                       placeholder="Ejemplo: Ingeniería en Sistemas"
                       class="w-full px-3 py-2 rounded-lg border text-xs transition-all focus:outline-none focus:ring-1"
                       style="background-color: var(--period-detail-bg); border-color: var(--period-detail-border); color: var(--period-detail-text-primary); --tw-ring-color: var(--period-detail-accent);">

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

        {{-- Footer --}}
        <div class="flex-shrink-0 flex items-center justify-end gap-2 px-3 sm:px-4 py-3"
             style="border-top: 1px solid var(--period-detail-border); background-color: var(--period-detail-card-bg);">

            <flux:modal.close>
                <button wire:click="closeModal"
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
                    {{ $careerId ? 'Guardar Cambios' : 'Crear Carrera' }}
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