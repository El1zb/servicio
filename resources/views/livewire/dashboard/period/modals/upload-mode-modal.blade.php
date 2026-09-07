{{-- =================== MODAL CAMBIO MODO DE CARGA =================== --}}
<flux:modal
    wire:model="isUploadModeChangeModalOpen"
    :dismissible="false"
    class="w-[95vw] sm:w-[450px] max-w-[95vw]">

    <div class="flex flex-col">

        {{-- ── Body ── --}}
        <div class="px-6 py-6" style="background-color: var(--color-modal-bg);">
            <p class="text-sm font-semibold mb-1" style="color: var(--color-primary-2);">
                Cambio de modo de carga
            </p>
            <p class="text-sm" style="color: var(--color-secondary);">
                Estás a punto de cambiar el modo de carga de este documento. Esto afectará todos los archivos subidos por el administrador para este documento.
            </p>
        </div>

        {{-- ── Footer ── --}}
        <div class="flex items-center justify-end gap-2 px-6 py-4 flex-shrink-0"
            style="border-top: 1px solid var(--color-border);">

            <button wire:click="cancelUploadModeChange"
                    class="px-4 py-2 rounded-full text-sm transition-colors duration-150 text-[var(--color-secondary)] hover:text-[var(--color-primary-2)] hover:bg-[var(--sidebar-color-hover)]">
                Cancelar
            </button>

            <button wire:click="confirmUploadModeChange" class="btn-danger">
                Confirmar
            </button>

        </div>

    </div>
</flux:modal>
