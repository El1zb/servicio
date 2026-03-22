{{-- Modal: Confirmar Eliminación --}}
@if($isDeleteModalOpen && $careerToDelete)
<flux:modal wire:model="isDeleteModalOpen" :dismissible="false"
            class="w-[95vw] sm:w-[420px] max-w-[95vw]">

    <div class="flex flex-col">

        {{-- Header --}}
        <div class="flex items-center gap-3 pb-4 shrink-0">
            <div class="w-9 h-9 rounded-lg bg-red-500/10 flex items-center justify-center flex-shrink-0">
                <svg class="w-4 h-4 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                </svg>
            </div>
            <div class="min-w-0 flex-1">
                <p class="text-sm font-bold text-[var(--color-primary-2)] leading-none">Confirmar Eliminación</p>
                <p class="text-xs text-[var(--color-secondary)] mt-1 leading-none truncate">
                    {{ optional(\App\Models\Career::find($careerToDelete))->name }}
                </p>
            </div>
        </div>

        {{-- Body --}}
        <div class="rounded-xl border border-[var(--color-border-hover)] bg-[var(--color-icon-bg)] p-4">
            <p class="text-sm font-medium text-[var(--color-primary-2)] mb-1">
                ¿Está seguro de que desea eliminar esta carrera?
            </p>
            <p class="text-xs text-[var(--color-secondary)]">
                Esta acción es irreversible y no podrá recuperarse.
            </p>
        </div>

        {{-- Footer --}}
        <div class="flex items-center justify-end gap-2 pt-4 border-t border-[var(--color-border-hover)] shrink-0">

            <button wire:click="$set('isDeleteModalOpen', false)"
                    class="px-4 py-2 text-xs font-medium rounded-lg transition-all duration-200 hover:-translate-y-0.5
                           text-[var(--color-secondary)] bg-transparent border border-transparent
                           hover:bg-[var(--color-icon-bg)] hover:text-[var(--color-primary-2)] hover:border-[var(--color-border-hover)]">
                Cancelar
            </button>

            <button wire:click="deleteCareer"
                    class="px-4 py-2 text-xs font-semibold rounded-lg transition-all duration-200
                           hover:-translate-y-0.5 hover:shadow-lg hover:opacity-85
                           flex items-center gap-2
                           bg-red-500/90 text-white">
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