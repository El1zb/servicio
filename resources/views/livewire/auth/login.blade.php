<div class="flex flex-col gap-6">

    <!-- Decoración de fondo -->
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
        <!-- Gradiente superior derecho -->
        <div class="absolute top-0 right-0 w-96 h-96 rounded-full blur-3xl"
            style="background: radial-gradient(circle, var(--index-icon-bg) 0%, transparent 70%)">
        </div>

        <!-- Gradiente inferior izquierdo -->
        <div class="absolute bottom-0 left-0 w-96 h-96 rounded-full blur-3xl"
            style="background: radial-gradient(circle, var(--index-icon-bg) 0%, transparent 70%)">
        </div>
    </div>

    <!-- Header -->
    <x-auth-header 
        :title="__('Bienvenido')" 
        :description="__('Ingresa tus credenciales para continuar')" 
        class="text-center text-[var(--index-text-primary)]"
    />

    <!-- Estado de la sesión -->
    <x-auth-session-status 
        class="text-center font-medium text-[var(--index-status-approved-text)]" 
        :status="session('status')" 
    />

    <!-- Formulario -->
    <form method="POST" wire:submit="login" class="flex flex-col gap-6">
        
        <!-- Correo Electrónico -->
        <flux:input
            wire:model="email"
            :label="__('Correo electrónico')"
            type="email"
            required
            autofocus
            autocomplete="email"
            placeholder="correo@ejemplo.com"
            style="background-color: var(--index-card-bg); color: var(--index-text-primary); border: 1px solid var(--index-border);"
                   
        />

        <!-- Contraseña -->
        <div class="relative">
            <flux:input
                wire:model="password"
                :label="__('Contraseña')"
                type="password"
                required
                autocomplete="current-password"
                :placeholder="__('Contraseña')"
                viewable
                style="background-color: var(--index-card-bg); color: var(--index-text-primary); border: 1px solid var(--index-border);"
                   
            />

            @if (Route::has('password.request'))
                <flux:link 
                    class="absolute end-0 top-0 text-sm hover:underline text-[var(--index-accent)]" 
                    :href="route('password.request')" 
                    wire:navigate
                >
                    {{ __('¿Olvidaste tu contraseña?') }}
                </flux:link>
            @endif
        </div>

        <!-- Recuérdame -->
        <flux:checkbox 
            wire:model="remember" 
            :label="__('Recuérdame')" 
            class="text-[var(--index-text-secondary)] transition-colors duration-200"
        />

        <!-- Botón de login -->
        <div class="flex items-center justify-end">
            <flux:button
                variant="primary"
                type="submit"
                wire:loading.attr="disabled"
                class="group relative w-full h-12
                    max-w-[280px] mx-auto
                    rounded-[var(--radius-md)]
                    bg-[var(--index-btn-primary-bg)] text-[var(--index-btn-primary-text)]
                    font-semibold text-base
                    shadow-lg shadow-[var(--index-btn-primary-shadow)]
                    hover:bg-[var(--index-btn-primary-hover)]
                    hover:shadow-xl hover:-translate-y-0.5
                    transition-all duration-300
                    disabled:opacity-60 disabled:cursor-not-allowed">

                <!-- Texto normal -->
                <span wire:loading.remove class="flex items-center justify-center gap-2">
                    {{ __('Iniciar sesión') }}
                </span>

                <!-- Estado cargando -->
                <span wire:loading class="flex items-center justify-center gap-2">
                    <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"/>
                    </svg>
                    {{ __('Validando...') }}
                </span>
            </flux:button>
        </div>

    </form>

    <!-- Registro -->
    @if (Route::has('register'))
        <div class="text-center text-sm text-[var(--index-text-secondary)] transition-colors duration-200">
            <span>{{ __('¿No tienes una cuenta?') }}</span>
            <flux:link 
                :href="route('register')" 
                wire:navigate 
                class="hover:underline transition-colors duration-200 text-[var(--index-accent)]"
            >
                {{ __('Regístrate') }}
            </flux:link>
        </div>
    @endif
</div>