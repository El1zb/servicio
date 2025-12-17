<div class="flex justify-center items-center min-h-screen bg-[var(--color-background)]">
    <div class="w-full max-w-md p-6 flex flex-col gap-6 bg-[var(--color-surface)] rounded-lg shadow-md">

        <!-- Header -->
        <x-auth-header
            :title="__('Crear cuenta administrativa')"
            :description="__('Ingresa los datos a continuación para crear un nuevo administrador')"
            class="text-center text-[var(--color-text-primary)]"
        />

        <!-- Mensaje flash -->
        @if(session()->has('message'))
            <div class="text-center text-[var(--color-success)] font-medium">
                {{ session('message') }}
            </div>
        @endif

        <!-- Formulario -->
        <form wire:submit.prevent="createAdmin" class="flex flex-col gap-6">

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

            <!-- Botón crear -->
            <div class="flex items-center justify-end">
                <flux:button
                    variant="primary"
                    type="submit"
                    wire:loading.attr="disabled"
                    class="group relative w-full h-12
                        rounded-[var(--radius-lg)]
                        font-extrabold text-[var(--color-text-inverse)]
                        bg-gradient-to-r
                        from-[var(--color-primary)]
                        to-[var(--color-primary-hover)]
                        shadow-[var(--shadow-md)]
                        hover:shadow-[var(--shadow-lg)]
                        transition-all duration-300
                        hover:-translate-y-[1px]
                        active:translate-y-0
                        disabled:opacity-60 disabled:cursor-not-allowed"
                >
                    <span wire:loading.remove class="flex items-center justify-center gap-2">
                        {{ __('Crear administrador') }}
                    </span>
                    <span wire:loading class="flex items-center justify-center gap-2">
                        <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                            <path class="opacity-75" fill="currentColor"
                                  d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"/>
                        </svg>
                        {{ __('Creando administrador...') }}
                    </span>
                </flux:button>
            </div>

        </form>

    </div>
</div>
