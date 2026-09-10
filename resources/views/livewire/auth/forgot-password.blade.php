<div class="flex flex-col gap-6 px-3 sm:px-0">

    {{-- Encabezado --}}
    <div class="text-center">
        <h1 class="auth-display-heading">{{ __('¿Olvidaste tu contraseña?') }}</h1>
        <p class="text-sm mt-2" style="color: var(--color-secondary);">
            {{ __('Ingresa tu correo y te enviaremos un enlace para restablecerla') }}
        </p>
    </div>

    {{-- Estado de la sesión --}}
    <x-auth-session-status
        class="text-center font-medium text-[var(--color-primary)]"
        :status="session('status')"
    />

    {{-- Formulario --}}
    <form method="POST" wire:submit="sendPasswordResetLink" class="flex flex-col gap-5">

        {{-- Correo electrónico --}}
        <div class="app-field">
            <label for="email" class="app-field-label">{{ __('Correo electrónico') }}</label>
            <input
                wire:model="email"
                id="email"
                type="email"
                required
                autocomplete="email"
                placeholder="correo@ejemplo.com"
                class="app-input w-full"
            >
            @error('email') <p class="app-field-error">{{ $message }}</p> @enderror
        </div>

        {{-- Botón enviar --}}
        <button type="submit" wire:loading.attr="disabled" class="btn-primary w-full justify-center disabled:opacity-60 disabled:cursor-not-allowed">
            <span wire:loading.remove>{{ __('Enviar enlace de recuperación') }}</span>
            <span wire:loading>{{ __('Enviando...') }}</span>
        </button>

    </form>

    {{-- Regresar a login --}}
    <div class="text-center text-sm" style="color: var(--color-secondary);">
        <span>{{ __('¿Ya la recordaste?') }}</span>
        <a href="{{ route('login') }}" wire:navigate class="hover:underline font-medium" style="color: var(--color-primary-2);">
            {{ __('Inicia sesión') }}
        </a>
    </div>

</div>
