{{-- Estado: Rechazado --}}
<div class="bg-[var(--index-card-bg)] border border-[var(--index-border)] p-6 rounded-xl shadow-lg">
    <div class="flex items-start gap-4">
        <div class="flex-shrink-0 mt-1">
            <div class="w-10 h-10 bg-[var(--index-status-rejected-bg)] rounded-full flex items-center justify-center">
                <svg class="w-6 h-6 text-[var(--index-status-rejected-icon)]" xmlns="http://www.w3.org/2000/svg"
                     fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
        </div>
        <div class="flex-1">
            <h3 class="text-[var(--index-text-primary)] font-semibold text-lg mb-2">
                Perfil Rechazado
            </h3>
            <p class="text-[var(--index-text-secondary)] text-sm leading-relaxed mb-3">
                Tu perfil no ha sido aprobado. Por favor, revisa la información y corrígela para volver a enviarla.
            </p>

            @if($student->rejection_reason)
                <div class="bg-[var(--index-content-bg)] border border-[var(--index-status-rejected-border)] p-4 rounded-lg mb-4">
                    <p class="text-xs font-semibold text-[var(--index-status-rejected-icon)] uppercase tracking-wide mb-2">
                        Motivo del Rechazo
                    </p>
                    <p class="text-sm text-[var(--index-text-secondary)] leading-relaxed">
                        {{ $student->rejection_reason }}
                    </p>
                </div>
            @endif

            <flux:button
                wire:click="$set('showForm', true)"
                class="group relative inline-flex items-center justify-center px-4 py-2
                            rounded-[var(--radius-md)]
                            bg-[var(--index-btn-primary-bg)]! text-[var(--index-btn-primary-text)]!
                            shadow-lg shadow-[var(--index-btn-primary-shadow)]
                            hover:bg-[var(--index-btn-primary-hover)]! hover:shadow-xl hover:-translate-y-0.5
                            transition-all duration-300 disabled:opacity-60 disabled:cursor-not-allowed gap-2">
                Editar Datos
            </flux:button>
        </div>
    </div>
</div>