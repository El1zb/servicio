{{-- Modal: Crear / Editar Semestre --}}
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
                    {{ $semesterId ? 'Editar Semestre' : 'Nuevo Semestre' }}
                </h3>
                <p class="text-xs mt-0.5 truncate" style="color: var(--period-detail-text-secondary);">
                    {{ $semesterId ? 'Modifique los datos del semestre' : 'Complete la información requerida' }}
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
                              d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                    Nombre del Semestre
                    <span style="color: rgba(239,68,68,0.8);">*</span>
                </p>

                <input type="text"
                       wire:model="name"
                       placeholder="Ejemplo: Semestre 1"
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