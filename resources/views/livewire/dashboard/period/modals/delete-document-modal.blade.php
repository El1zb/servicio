{{-- =================== MODAL ELIMINAR DOCUMENTO =================== --}}
<flux:modal
    wire:model="isDeleteDocumentModalOpen"
    :dismissible="false"
    class="w-[95vw] sm:w-[420px] max-w-[95vw]">

    <div class="flex flex-col">

        {{-- ── Body ── --}}
        <div class="px-4 sm:px-6 py-5 sm:py-6"
            style="background-color: var(--color-card-bg);">

            <div class="p-3 sm:p-4 rounded-lg sm:rounded-xl border"
                style="background-color: var(--color-icon-bg); border-color: var(--color-border-hover);">

                <div class="flex items-start gap-3">
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium mb-1" style="color: var(--color-primary-2);">
                            ¿Está seguro de que desea eliminar este documento?
                        </p>
                        <p class="text-xs" style="color: var(--color-secondary);">
                            Esta acción es irreversible y no podrá recuperarse.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        {{-- ── Footer ── --}}
        <div class="flex flex-col-reverse sm:flex-row items-center justify-end gap-2 px-4 sm:px-6 py-3 sm:py-4 flex-shrink-0"
            style="border-top: 1px solid var(--color-border-hover); background-color: var(--color-card-bg);">

            <button wire:click="$set('isDeleteDocumentModalOpen', false)"
                class="w-full sm:w-auto px-4 py-2 rounded-lg text-sm transition-all duration-200 hover:-translate-y-0.5 text-center"
                style="color: var(--color-secondary); background-color: transparent;"
                onmouseover="this.style.color='var(--color-primary-2)'; this.style.backgroundColor='var(--color-border-hover)'"
                onmouseout="this.style.color='var(--color-secondary)'; this.style.backgroundColor='transparent'">
                Cancelar
            </button>

            <button wire:click="confirmDeleteDocument"
                class="w-full sm:w-auto px-5 py-2 rounded-lg text-sm font-semibold transition-all duration-200 hover:-translate-y-0.5 hover:shadow-lg flex items-center justify-center gap-2"
                style="background-color: rgba(239,68,68,0.9); color: white;"
                onmouseover="this.style.opacity='0.85'"
                onmouseout="this.style.opacity='1'">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                </svg>
                Eliminar
            </button>
        </div>

    </div>
</flux:modal>