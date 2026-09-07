<div class="flex flex-col gap-6">

    {{-- Encabezado --}}
    <div class="text-center">
        <h1 class="auth-display-heading">{{ __('Crea una cuenta') }}</h1>
        <p class="text-sm mt-2" style="color: var(--color-secondary);">
            {{ __('Ingresa tus datos a continuación para crear tu cuenta') }}
        </p>
    </div>

    {{-- Estado de la sesión --}}
    <x-auth-session-status
        class="text-center font-medium text-[var(--color-primary)]"
        :status="session('status')"
    />

    {{-- Formulario --}}
    <form method="POST" wire:submit="register" class="flex flex-col gap-5">

        {{-- Nombre --}}
        <div class="app-field">
            <label for="name" class="app-field-label">{{ __('Nombre completo') }}</label>
            <input
                wire:model="name"
                id="name"
                type="text"
                required
                autofocus
                autocomplete="name"
                placeholder="Nombre completo"
                class="app-input w-full"
            >
            @error('name') <p class="app-field-error">{{ $message }}</p> @enderror
        </div>

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
            <label for="password" class="app-field-label">{{ __('Contraseña') }}</label>
            <input
                wire:model="password"
                id="password"
                type="password"
                required
                autocomplete="new-password"
                placeholder="Contraseña"
                class="app-input w-full"
            >
            @error('password') <p class="app-field-error">{{ $message }}</p> @enderror
        </div>

        {{-- Confirmar contraseña --}}
        <div class="app-field">
            <label for="password_confirmation" class="app-field-label">{{ __('Confirmar contraseña') }}</label>
            <input
                wire:model="password_confirmation"
                id="password_confirmation"
                type="password"
                required
                autocomplete="new-password"
                placeholder="Confirmar contraseña"
                class="app-input w-full"
            >
            @error('password_confirmation') <p class="app-field-error">{{ $message }}</p> @enderror
        </div>

        {{-- Botón registrar --}}
        <button type="submit" wire:loading.attr="disabled" class="btn-primary w-full justify-center disabled:opacity-60 disabled:cursor-not-allowed">
            <span wire:loading.remove>{{ __('Crear cuenta') }}</span>
            <span wire:loading>{{ __('Creando cuenta...') }}</span>
        </button>

    </form>

    {{-- Login --}}
    <div class="text-center text-sm" style="color: var(--color-secondary);">
        <span>{{ __('¿Ya tienes una cuenta?') }}</span>
        <a href="{{ route('login') }}" wire:navigate class="hover:underline font-medium" style="color: var(--color-primary-2);">
            {{ __('Inicia sesión') }}
        </a>
    </div>

</div>
