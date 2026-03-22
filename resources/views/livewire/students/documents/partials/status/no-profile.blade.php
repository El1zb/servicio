{{-- Sin perfil de estudiante --}}
<div class="flex items-center justify-center min-h-[80vh] px-4">
    <div class="max-w-md w-full">
        <div class="bg-[var(--color-card-bg)] rounded-2xl p-8 text-center">
            <div class="relative w-20 h-20 bg-[var(--color-icon-bg)] rounded-full flex items-center justify-center mx-auto mb-6
                        before:content-[''] before:absolute before:inset-0 before:rounded-full before:bg-[var(--color-icon-bg)]
                        before:animate-ping before:opacity-20">
                <i class="fas fa-user-slash text-4xl text-[var(--color-secondary)] relative z-10"></i>
            </div>
            <h2 class="text-2xl font-bold text-[var(--color-primary-2)] mb-3">Sin Perfil de Estudiante</h2>
            <p class="text-[var(--color-secondary)] mb-8 leading-relaxed max-w-sm mx-auto">
                Para acceder a los documentos académicos, necesitas tener un perfil de estudiante activo en el sistema.
            </p>
            <a href="{{ route('students.profile') }}"
               class="group inline-flex items-center justify-center px-4 py-2 rounded-lg gap-2
                      bg-[var(--color-icon-bg)] text-[var(--color-primary-2)]
                      border border-[var(--color-border-hover)]
                      hover:bg-[var(--color-icon-bg-hover)] hover:text-[var(--color-icon-hover)] hover:-translate-y-0.5
                      transition-all duration-300">
                <span>Completar perfil</span>
                <i class="fas fa-arrow-right text-sm group-hover:translate-x-1 transition-transform"></i>
            </a>
        </div>
    </div>
</div>