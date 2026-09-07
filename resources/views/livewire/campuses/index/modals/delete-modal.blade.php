{{-- =================== MODAL ELIMINAR CAMPUS =================== --}}
@if($isDeleteModalOpen && $campusToDelete)
<flux:modal
    wire:model="isDeleteModalOpen"
    :dismissible="false"
    class="w-[95vw] sm:w-[420px] max-w-[95vw]"
    style="border-color: var(--color-border);">

    <div class="flex flex-col">

        {{-- ── Body ── --}}
        <div class="px-6 py-6" style="background-color: var(--color-modal-bg);">
            <p class="text-sm font-semibold mb-1" style="color: var(--color-primary-2);">
                ¿Eliminar este campus?
            </p>
            <p class="text-sm" style="color: var(--color-secondary);">
                {{ optional(\App\Models\Campus::find($campusToDelete))->name }}
                — se moverá a la papelera; podrás restaurarlo desde Configuración &gt; Papelera durante 30 días.
            </p>
        </div>

        {{-- ── Footer ── --}}
        <div class="flex items-center justify-end gap-2 px-6 py-4 flex-shrink-0"
            style="border-top: 1px solid var(--color-border);">

            <button wire:click="$set('isDeleteModalOpen', false)"
                    class="px-4 py-2 rounded-full text-sm transition-colors duration-150 text-[var(--color-secondary)] hover:text-[var(--color-primary-2)] hover:bg-[var(--sidebar-color-hover)]">
                Cancelar
            </button>

            <button wire:click="deleteCampus" class="btn-danger">
                Eliminar
            </button>
        </div>

    </div>
</flux:modal>
@endif
