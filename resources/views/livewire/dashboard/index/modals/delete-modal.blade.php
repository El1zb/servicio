<flux:modal wire:model="isDeleteModalOpen" :dismissible="false"
            class="w-[95vw] sm:w-[380px] max-w-[95vw]">
    <div class="flex flex-col">

        <div class="px-6 py-5">
            <p class="text-sm font-semibold mb-1" style="color: var(--index-text-secondary);">
                ¿Seguro que deseas eliminar este periodo?
            </p>
            <p class="text-xs" style="color: var(--index-text-primary);">
                Esta acción es irreversible.
            </p>
        </div>

        <div class="px-6 py-4 flex items-center justify-end gap-2 border-t"
             style="border-color: var(--index-border);">

            <button wire:click="$set('isDeleteModalOpen', false)"
                    class="px-4 py-2 rounded-lg text-sm transition-all duration-300 hover:-translate-y-0.5"
                    style="color: var(--index-text-secondary);"
                    onmouseover="this.style.color='var(--index-text-primary)'; this.style.backgroundColor='var(--index-border)'"
                    onmouseout="this.style.color='var(--index-text-secondary)'; this.style.backgroundColor='transparent'">
                Cancelar
            </button>

            <button wire:click="deletePeriod"
                    class="px-4 py-2 rounded-lg text-sm font-medium shadow-lg text-white
                           transition-all duration-300 hover:-translate-y-0.5 hover:shadow-xl"
                    style="background-color: var(--index-btn-danger-bg);"
                    onmouseover="this.style.opacity='0.85'"
                    onmouseout="this.style.opacity='1'">
                Eliminar
            </button>

        </div>

    </div>
</flux:modal>