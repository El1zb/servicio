{{-- Modal: Confirmar Eliminación --}}
@if($isDeleteModalOpen && $campusToDelete)
<flux:modal wire:model="isDeleteModalOpen" :dismissible="false"
            class="w-[95vw] sm:w-[420px] max-w-[95vw]">

    <div class="flex flex-col">

        {{-- Header --}}
        <div class="flex items-center justify-between px-4 sm:px-6 py-3 sm:py-4 flex-shrink-0"
             style="border-bottom: 1px solid var(--period-detail-border); background-color: var(--period-detail-card-bg);">
            <div class="min-w-0 flex-1">
                <h3 class="text-sm font-semibold leading-tight truncate"
                    style="color: var(--period-detail-text-primary);">
                    Confirmar Eliminación
                </h3>
                <p class="text-xs mt-0.5 truncate" style="color: var(--period-detail-text-secondary);">
                    {{ optional(\App\Models\Campus::find($campusToDelete))->name }}
                </p>
            </div>
        </div>

        {{-- Body --}}
        <div class="px-4 sm:px-6 py-5 sm:py-6"
             style="background-color: var(--period-detail-card-bg);">
            <div class="p-3 sm:p-4 rounded-lg sm:rounded-xl border"
                 style="background-color: var(--period-detail-bg); border-color: var(--period-detail-border);">
                <div class="flex items-start gap-3">
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium mb-1" style="color: var(--period-detail-text-primary);">
                            ¿Está seguro de que desea eliminar este campus?
                        </p>
                        <p class="text-xs" style="color: var(--period-detail-text-secondary);">
                            Esta acción es irreversible y no podrá recuperarse.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Footer --}}
        <div class="flex items-center justify-end gap-2 px-4 sm:px-6 py-3 sm:py-4 flex-shrink-0"
             style="border-top: 1px solid var(--period-detail-border); background-color: var(--period-detail-card-bg);">

            <button wire:click="$set('isDeleteModalOpen', false)"
                    class="px-4 py-2 rounded-lg text-xs font-medium transition-all duration-200 hover:-translate-y-0.5"
                    style="color: var(--period-detail-text-secondary); background-color: transparent;"
                    onmouseover="this.style.color='var(--period-detail-text-primary)'; this.style.backgroundColor='var(--period-detail-border)'"
                    onmouseout="this.style.color='var(--period-detail-text-secondary)'; this.style.backgroundColor='transparent'">
                Cancelar
            </button>

            <button wire:click="deleteCampus"
                    class="px-5 py-2 rounded-lg text-xs font-semibold transition-all duration-200 hover:-translate-y-0.5 hover:shadow-lg flex items-center gap-2"
                    style="background-color: var(--period-detail-btn-reject-bg); color: var(--period-detail-btn-reject-text);"
                    onmouseover="this.style.opacity='0.85'"
                    onmouseout="this.style.opacity='1'">
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