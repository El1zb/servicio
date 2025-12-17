<div class="mt-4 flex flex-col gap-6">

    <!-- Mensaje principal -->
    <flux:text class="text-center text-[var(--color-text-primary)] leading-relaxed">
        {{ __('Por favor verifica tu correo electrónico haciendo clic en el enlace que acabamos de enviarte.') }}
    </flux:text>

    <!-- Estado enviado -->
    @if (session('status') === 'verification-link-sent')
        <flux:text
            class="text-center font-medium
                   text-[var(--color-success)]
                   transition-colors duration-200"
        >
            {{ __('Se ha enviado un nuevo enlace de verificación al correo electrónico proporcionado.') }}
        </flux:text>
    @endif

    <!-- Acciones -->
    <div class="flex flex-col items-center justify-between gap-3">

        <!-- Reenviar correo -->
        <flux:button
            wire:click="sendVerification"
            variant="primary"
            wire:loading.attr="disabled"
            class="group relative w-full h-12
                max-w-[280px] mx-auto
                rounded-[var(--radius-md)]
                bg-[var(--color-btn-primary)] text-[var(--text-btn-primary)]
                font-[var(--font-semibold)] text-[length:var(--text-base)]
                shadow-lg shadow-[var(--color-btn-primary-shadow)]
                hover:bg-[var(--color-btn-primary-hover)]
                hover:shadow-xl hover:-translate-y-0.5
                transition-all duration-300
                disabled:opacity-60 disabled:cursor-not-allowed">

            <!-- Texto normal -->
            <span wire:loading.remove class="flex items-center justify-center gap-2">
                {{ __('Reenviar correo de verificación') }}
            </span>

            <!-- Estado cargando -->
            <span wire:loading class="flex items-center justify-center gap-2">
                <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                    <path class="opacity-75" fill="currentColor"
                          d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"/>
                </svg>
                {{ __('Enviando...') }}
            </span>

        </flux:button>

        <!-- Cerrar sesión -->
        <flux:link
            class="text-sm text-[var(--color-text-secondary)]
                   hover:underline transition-colors duration-200
                   cursor-pointer"
            wire:click="logout"
        >
            {{ __('Cerrar sesión') }}
        </flux:link>

    </div>

</div>
