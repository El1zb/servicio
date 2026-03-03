{{-- Modal: Observaciones del Revisor --}}
@if($isCommentsModalOpen && $selectedDocument)
    <flux:modal :dismissible="false" wire:model="isCommentsModalOpen"
                class="w-[95vw] sm:w-[85vw] md:w-[600px] lg:w-[650px] max-w-[700px]">

        <div class="flex flex-col h-[75vh] sm:h-[70vh] max-h-[600px]">

            {{-- Header --}}
            <div class="flex items-start gap-3 pb-4 shrink-0">
                <div class="min-w-0 flex-1">
                    <flux:heading size="lg" class="text-[var(--modal-text-primary)] mb-1">Observaciones del Revisor</flux:heading>
                    <p class="text-xs sm:text-sm text-[var(--modal-text-secondary)]">Comentarios y sugerencias sobre tu documento</p>
                </div>
            </div>

            {{-- Contenido --}}
            <div class="flex-1 overflow-y-auto overflow-x-hidden py-4 sidebar-scroll min-h-0">
                @if($selectedDocument->comments)
                    <div class="pl-2 pr-2">
                        <div class="bg-[var(--index-content-bg)] rounded-2xl p-5 sm:p-6 shadow-sm border border-[var(--index-border)]">
                            <div class="flex items-center gap-2 mb-3 pb-3 border-b border-[var(--index-border)]">
                                <i class="fas fa-user-circle text-lg text-[var(--index-accent)]"></i>
                                <span class="text-sm font-medium text-[var(--modal-text-primary)]">Revisor</span>
                            </div>
                            <div class="whitespace-pre-line break-words text-[var(--modal-text-secondary)] leading-relaxed text-sm sm:text-base">
                                {{ $selectedDocument->comments }}
                            </div>
                        </div>
                    </div>
                @else
                    <div class="h-full flex flex-col items-center justify-center text-center px-4 py-12">
                        <div class="relative mb-6">
                            <div class="inline-flex items-center justify-center w-20 h-20 bg-[var(--index-content-bg)] rounded-2xl shadow-lg border border-[var(--index-border)]">
                                <i class="fas fa-inbox text-4xl text-[var(--index-text-secondary)]"></i>
                            </div>
                            <div class="absolute -bottom-1 -right-1 w-8 h-8 bg-[var(--index-accent)] rounded-full flex items-center justify-center shadow-lg">
                                <i class="fas fa-check text-white text-sm"></i>
                            </div>
                        </div>
                        <p class="text-base font-medium text-[var(--modal-text-primary)] mb-2">Sin observaciones</p>
                        <p class="text-sm text-[var(--modal-text-secondary)] max-w-xs">No hay comentarios del revisor para este documento</p>
                    </div>
                @endif
            </div>

            {{-- Footer --}}
            <div class="flex justify-end gap-3 pt-4 border-t dark:border-gray-700 shrink-0">
                <flux:button wire:click="$set('isCommentsModalOpen', false)"
                    class="group relative inline-flex items-center justify-center px-4 py-2 rounded-[var(--radius-md)]
                           bg-[var(--modal-btn-document-cerrar)]! text-[var(--modal-btn-document-cerrar-text)]!
                           shadow-lg shadow-[var(--modal-btn-document-cerrar-shadow)]
                           hover:bg-[var(--modal-btn-document-cerrar-hover)]! hover:shadow-xl hover:-translate-y-0.5
                           transition-all duration-300 disabled:opacity-60 disabled:cursor-not-allowed gap-2">
                    Cerrar
                </flux:button>
            </div>

        </div>
    </flux:modal>
@endif