{{-- Modal: Observaciones del Revisor --}}
@if($isCommentsModalOpen && $selectedDocument)
    <flux:modal :dismissible="false" wire:model="isCommentsModalOpen"
                class="w-[95vw] sm:w-[85vw] md:w-[600px] lg:w-[650px] max-w-[700px]">

        <div class="flex flex-col max-h-[70vh]">

            {{-- Header --}}
            <div class="flex items-start gap-3 pb-4 shrink-0">
                <div class="w-9 h-9 rounded-lg bg-[var(--color-icon-bg)] flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-comment-dots text-[var(--color-secondary)] text-sm"></i>
                </div>
                <div class="min-w-0 flex-1">
                    <flux:heading size="lg" class="text-[var(--color-primary-2)] mb-0.5">Observaciones del Revisor</flux:heading>
                    <p class="text-xs text-[var(--color-secondary)]">{{ $selectedDocument->name }}</p>
                </div>
            </div>

            {{-- Contenido --}}
            <div class="overflow-y-auto sidebar-scroll min-h-0">
                @if($selectedDocument->comments)
                    <div class="rounded-xl bg-[var(--color-icon-bg)] border border-[var(--color-border-hover)] p-4">
                        <div class="flex items-center gap-2 mb-3">
                            <i class="fas fa-user-circle text-[var(--color-secondary)] text-base"></i>
                            <span class="text-xs font-semibold text-[var(--color-secondary)] uppercase tracking-wide">Revisor</span>
                        </div>
                        <p class="whitespace-pre-line break-words text-[var(--color-primary-2)] leading-relaxed text-sm">
                            {{ $selectedDocument->comments }}
                        </p>
                    </div>
                @else
                    <div class="flex flex-col items-center justify-center text-center px-4 py-12">
                        <div class="relative mb-4">
                            <div class="inline-flex items-center justify-center w-16 h-16 bg-[var(--color-icon-bg)] rounded-2xl border border-[var(--color-border-hover)]">
                                <i class="fas fa-inbox text-3xl text-[var(--color-secondary)]"></i>
                            </div>
                            <div class="absolute -bottom-1 -right-1 w-6 h-6 bg-[var(--color-primary)] rounded-full flex items-center justify-center">
                                <i class="fas fa-check text-white text-[10px]"></i>
                            </div>
                        </div>
                        <p class="text-sm font-medium text-[var(--color-primary-2)] mb-1">Sin observaciones</p>
                        <p class="text-xs text-[var(--color-secondary)]">No hay comentarios del revisor para este documento</p>
                    </div>
                @endif
            </div>

        </div>
    </flux:modal>
@endif