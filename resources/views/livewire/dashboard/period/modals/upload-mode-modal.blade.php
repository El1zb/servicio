{{-- =================== MODAL CAMBIO MODO DE CARGA =================== --}}
<flux:modal
    wire:model="isUploadModeChangeModalOpen"
    :dismissible="false"
    class="w-[95vw] sm:w-[450px] max-w-[95vw]">

    <div class="flex flex-col">

        {{-- ── Body ── --}}
        <div class="px-4 sm:px-6 py-5 sm:py-6"
            style="background-color: var(--period-detail-card-bg);">

            <div class="p-3 sm:p-4 rounded-lg sm:rounded-xl border"
                style="background-color: var(--period-detail-bg); border-color: var(--period-detail-border);">

                <div class="flex items-start gap-3">
                    <div class="flex-1 min-w-0">
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

        {{-- ── Footer ── --}}
        <div class="flex flex-col-reverse sm:flex-row items-center justify-end gap-2 px-4 sm:px-6 py-3 sm:py-4 flex-shrink-0"
            style="border-top: 1px solid var(--period-detail-border); background-color: var(--period-detail-card-bg);">

            <button wire:click="$set('isUploadModeChangeModalOpen', false)"
                class="w-full sm:w-auto px-4 py-2 rounded-lg text-sm transition-all duration-200 hover:-translate-y-0.5 text-center"
                style="color: var(--period-detail-text-secondary); background-color: transparent;"
                onmouseover="this.style.color='var(--period-detail-text-primary)'; this.style.backgroundColor='var(--period-detail-border)'"
                onmouseout="this.style.color='var(--period-detail-text-secondary)'; this.style.backgroundColor='transparent'">
                Cancelar
            </button>

            <button wire:click="confirmUploadModeChange"
                class="w-full sm:w-auto px-5 py-2 rounded-lg text-sm font-semibold transition-all duration-200 hover:-translate-y-0.5 hover:shadow-lg flex items-center justify-center gap-2"
                style="background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); color: #ffffff;"
                onmouseover="this.style.opacity='0.85'"
                onmouseout="this.style.opacity='1'">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                </svg>
                Confirmar
            </button>

        </div>

    </div>
</flux:modal>