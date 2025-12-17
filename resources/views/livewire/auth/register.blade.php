<div class="flex flex-col gap-6">

    <!-- Decoración de fondo -->
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
        <!-- Gradiente superior derecho -->
        <div class="absolute top-0 right-0 w-96 h-96 
            bg-gradient-to-br from-[#1e2c4d]/20 to-transparent 
            dark:from-[#3c78c7]/10 
            rounded-full blur-3xl">
        </div>

        <!-- Gradiente inferior izquierdo -->
        <div class="absolute bottom-0 left-0 w-96 h-96 
            bg-gradient-to-tr from-[#263455]/20 to-transparent  
            dark:from-[#5A7BA5]/10 
            rounded-full blur-3xl">
        </div>
    </div>

    <!-- Header -->
    <x-auth-header
        :title="__('Crea una cuenta')"
        :description="__('Ingresa tus datos a continuación para crear tu cuenta')"
        class="text-center text-[var(--color-text-primary)]"
    />

    <!-- Estado de la sesión -->
    <x-auth-session-status
        class="text-center text-[var(--color-success)] font-medium"
        :status="session('status')"
    />

    <!-- Formulario -->
    <form method="POST" wire:submit="register" class="flex flex-col gap-6">

        <!-- Nombre -->
        <flux:input
            wire:model="name"
            :label="__('Nombre completo')"
            type="text"
            required
            autofocus
            autocomplete="name"
            placeholder="Nombre completo"
        />

        <!-- Correo electrónico -->
        <flux:input
            wire:model="email"
            :label="__('Correo electrónico')"
            type="email"
            required
            autocomplete="email"
            placeholder="correo@ejemplo.com"
        />

        <!-- Contraseña -->
        <flux:input
            wire:model="password"
            :label="__('Contraseña')"
            type="password"
            required
            autocomplete="new-password"
            placeholder="Contraseña"
            viewable
        />

        <!-- Confirmar contraseña -->
        <flux:input
            wire:model="password_confirmation"
            :label="__('Confirmar contraseña')"
            type="password"
            required
            autocomplete="new-password"
            placeholder="Confirmar contraseña"
            viewable
        />

        <!-- Botón registrar -->
        <div class="flex items-center justify-end">
            <flux:button
                variant="primary"
                type="submit"
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
                    {{ __('Crear cuenta') }}
                </span>

                <!-- Estado cargando -->
                <span wire:loading class="flex items-center justify-center gap-2">
                    <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                        <path class="opacity-75" fill="currentColor"
                              d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"/>
                    </svg>
                    {{ __('Creando cuenta...') }}
                </span>

            </flux:button>
        </div>

    </form>

    <!-- Login -->
    <div class="text-center text-sm text-[var(--color-text-secondary)] transition-colors duration-200">
        <span>{{ __('¿Ya tienes una cuenta?') }}</span>
        <flux:link
            :href="route('login')"
            wire:navigate
            class="hover:underline transition-colors duration-200"
        >
            {{ __('Inicia sesión') }}
        </flux:link>
    </div>

</div>
