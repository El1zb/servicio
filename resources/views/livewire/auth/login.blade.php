<div class="flex flex-col gap-6 px-3 sm:px-0">

    {{-- Encabezado --}}
    <div class="text-center">
        <h1 class="auth-display-heading">{{ __('Bienvenido') }}</h1>
        <p class="text-sm mt-2" style="color: var(--color-secondary);">
            {{ __('Ingresa tu correo y contraseña para acceder a tu cuenta') }}
        </p>
    </div>

    {{-- Estado de la sesión --}}
    <x-auth-session-status
        class="text-center font-medium text-[var(--color-primary)]"
        :status="session('status')"
    />

    {{-- Formulario --}}
    <form method="POST" wire:submit="login" class="flex flex-col gap-5">

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

        {{-- Contraseña --}}
        <div class="app-field">
            <div class="flex items-center justify-between mb-1.5">
                <label for="password" class="app-field-label" style="margin-bottom:0;">{{ __('Contraseña') }}</label>
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" wire:navigate class="text-xs hover:underline" style="color: var(--color-primary-2);">
                        {{ __('¿Olvidaste tu contraseña?') }}
                    </a>
                @endif
            </div>
            <input
                wire:model="password"
                id="password"
                type="password"
                required
                autocomplete="current-password"
                placeholder="{{ __('Contraseña') }}"
                class="app-input w-full"
            >
            @error('password') <p class="app-field-error">{{ $message }}</p> @enderror
        </div>

        {{-- Recuérdame --}}
        <label class="flex items-center gap-2.5 cursor-pointer -mt-1">
            <input type="checkbox" wire:model="remember" class="sr-only">
            <span class="individual-toggle-check {{ $remember ? 'is-checked' : '' }}">
                <svg width="9" height="9" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3.5" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6L9 17l-5-5"/></svg>
            </span>
            <span class="text-sm" style="color: var(--color-secondary);">{{ __('Recuérdame') }}</span>
        </label>

        {{-- Botón de login --}}
        <button type="submit" wire:loading.attr="disabled" class="flex w-full h-10 items-center justify-center gap-2 rounded-full bg-black text-white text-sm hover:bg-neutral-800 transition-colors disabled:opacity-60 disabled:cursor-not-allowed">
            <span wire:loading.remove>{{ __('Iniciar sesión') }}</span>
            <span wire:loading>{{ __('Validando...') }}</span>
        </button>

    </form>

    {{-- Registro --}}
    @if (Route::has('register'))
        <div class="text-center text-sm" style="color: var(--color-secondary);">
            <span>{{ __('¿No tienes una cuenta?') }}</span>
            <a href="{{ route('register') }}" wire:navigate class="hover:underline font-medium" style="color: var(--color-primary-2);">
                {{ __('Regístrate') }}
            </a>
        </div>
    @endif
</div>
