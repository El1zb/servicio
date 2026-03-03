{{-- Perfil rechazado --}}
<div class="flex items-center justify-center min-h-[80vh] px-4">
    <div class="max-w-md w-full">
        <div class="bg-[var(--index-card-bg)] rounded-2xl shadow-2xl p-8 text-center backdrop-blur-sm">
            <div class="relative w-20 h-20 bg-[var(--index-status-rejected-bg)] rounded-full flex items-center justify-center mx-auto mb-6
                        before:content-[''] before:absolute before:inset-0 before:rounded-full
                        before:bg-[var(--index-status-rejected-bg)] before:animate-ping before:opacity-20">
                <i class="fas fa-times-circle text-4xl text-[var(--index-status-rejected-icon)] relative z-10"></i>
            </div>
            <h2 class="text-2xl font-bold text-[var(--index-text-primary)] mb-3">Perfil rechazado</h2>
            <p class="text-[var(--index-text-secondary)] mb-6 leading-relaxed max-w-sm mx-auto">
                Por favor, actualiza la información necesaria y vuelve a enviarlo para su revisión.
            </p>
            <a href="{{ route('students.profile') }}"
               class="inline-flex items-center gap-2 rounded-[var(--radius-md)]
                      bg-[var(--settings-btn-primary)]! text-[var(--settings-btn-primary-text)]!
                      shadow-lg shadow-[var(--settings-btn-primary-shadow)]
                      hover:bg-[var(--settings-btn-primary-hover)]! hover:shadow-xl hover:-translate-y-0.5
                      active:translate-y-0 transition-all duration-300 px-6 py-3 font-medium">
                <span>Corregir perfil</span>
                <i class="fas fa-edit text-sm"></i>
            </a>
        </div>
    </div>
</div>