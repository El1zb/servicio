<div class="flex flex-col gap-6">

    <!-- Decoración de fondo -->
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
        <!-- Gradiente superior derecho -->
        <div class="absolute top-0 right-0 w-96 h-96 rounded-full blur-3xl"
            style="background: radial-gradient(circle, var(--color-icon-bg) 0%, transparent 70%)">
        </div>

        <!-- Gradiente inferior izquierdo -->
        <div class="absolute bottom-0 left-0 w-96 h-96 rounded-full blur-3xl"
            style="background: radial-gradient(circle, var(--color-icon-bg) 0%, transparent 70%)">
        </div>
    </div>

    <!-- Header -->
    <x-auth-header
        :title="__('¿Olvidaste tu contraseña?')"
        :description="__('Ingresa tu correo y te enviaremos un enlace para restablecerla')"
        class="text-center text-[var(--color-primary-2)]"
    />

    <!-- Estado de la sesión -->
    <x-auth-session-status
        class="text-center font-medium text-[var(--color-primary)]"
        :status="session('status')"
    />

    <!-- Formulario -->
    <form method="POST" wire:submit="sendPasswordResetLink" class="flex flex-col gap-6">

        <!-- Correo electrónico -->
        <flux:input
            wire:model.lazy="email"
            :label="__('Correo electrónico')"
            type="email"
            required
            autofocus
            autocomplete="email"
            placeholder="correo@ejemplo.com"
            style="background-color: var(--color-card-bg); color: var(--color-primary-2); border: 1px solid var(--color-border);"
        />

        <!-- Botón enviar -->
        <div class="flex items-center justify-end">
            <flux:button
                variant="primary"
                type="submit"
                wire:loading.attr="disabled"
                class="group relative w-full h-12
                    max-w-[280px] mx-auto
                    rounded-[var(--radius-md)]
                    bg-[var(--color-primary)]! text-[var(--color-bg)]!
                    font-semibold text-base
                    shadow-lg shadow-[var(--color-border-hover)]
                    hover:bg-[var(--color-primary-2)]!
                    hover:shadow-xl hover:-translate-y-0.5
                    transition-all duration-300
                    disabled:opacity-60 disabled:cursor-not-allowed">

                <!-- Texto normal -->
                <span wire:loading.remove class="flex items-center justify-center gap-2">
                    {{ __('Enviar enlace de recuperación') }}
                </span>

                <!-- Estado cargando -->
                <span wire:loading class="flex items-center justify-center gap-2">
                    <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                        <path class="opacity-75" fill="currentColor"
                              d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"/>
                    </svg>
                    {{ __('Enviando enlace...') }}
                </span>

            </flux:button>
        </div>

    </form>

    <!-- Regresar a login -->
    <div class="text-center text-sm text-[var(--color-secondary)] transition-colors duration-200">
        <span>{{ __('¿Ya la recordaste?') }}</span>
        <flux:link
            :href="route('login')"
            wire:navigate
            class="hover:underline transition-colors duration-200 text-[var(--color-primary)]"
        >
            {{ __('Inicia sesión') }}
        </flux:link>
    </div>

</div>