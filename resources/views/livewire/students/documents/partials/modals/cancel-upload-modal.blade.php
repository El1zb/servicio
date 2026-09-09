{{-- =================== MODAL CANCELAR ENTREGA ===================
     Mismo diseño que "Eliminar cuenta" en configuración
     (settings/delete-user-form.blade.php). --}}
@if($isCancelModalOpen && $cancelDocId)
    @php
        $cancelDocument = $this->cancelDocument;
    @endphp

    @if($cancelDocument)
        <flux:modal
            wire:model="isCancelModalOpen"
            :dismissible="false"
            :closable="false"
            focusable
            class="w-[95vw] sm:w-[90vw] md:w-[460px] max-w-[95vw]"
            style="border-color: var(--color-border);">

            <div class="flex flex-col">

                {{-- ── Header ── --}}
                <div class="px-6 py-5" style="border-bottom: 1px solid var(--color-border);">
                    <h2 class="text-lg font-semibold leading-tight" style="color: var(--color-primary-2);">
                        ¿Cancelar esta entrega?
                    </h2>
                    <p class="text-sm mt-0.5" style="color: var(--color-secondary);">
                        Se eliminará el archivo que subiste para "{{ $cancelDocument->name }}" y podrás volver a subirlo.
                    </p>
                </div>

                {{-- ── Footer ── --}}
                <div class="flex items-center justify-end gap-2 px-6 py-4"
                    style="border-top: 1px solid var(--color-border);">

                    <button type="button" wire:click="closeCancelModal"
                            class="px-4 py-2 rounded-full text-sm transition-colors duration-150 text-[var(--color-secondary)] hover:text-[var(--color-primary-2)] hover:bg-[var(--sidebar-color-hover)]">
                        Volver
                    </button>

                    <button type="button" wire:click="confirmCancelUpload"
                            wire:loading.attr="disabled"
                            wire:target="confirmCancelUpload"
                            class="btn-danger disabled:opacity-60 disabled:cursor-not-allowed">
                        <span wire:loading.remove wire:target="confirmCancelUpload">Cancelar entrega</span>
                        <span wire:loading wire:target="confirmCancelUpload">Cancelando...</span>
                    </button>
                </div>

            </div>
        </flux:modal>
    @endif
@endif
