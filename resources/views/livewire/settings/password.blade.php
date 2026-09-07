<div>
    {{-- Mensaje flash --}}
    @if (session()->has('message'))
        <div class="rounded-xl p-4 border flex items-center gap-3 mb-6"
             style="background-color: var(--color-card-bg);
                    border-color: var(--color-border-hover);">
            <div class="flex-shrink-0">
                <svg class="w-5 h-5" style="color: var(--color-primary-2);"
                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <p class="text-sm font-medium" style="color: var(--color-primary-2);">
                {{ session('message') }}
            </p>
        </div>
    @endif

    {{-- Formulario de actualización de contraseña --}}
    <form method="POST" wire:submit.prevent="updatePassword" class="space-y-6">

        <div class="app-field">
            <label for="current_password" class="app-field-label">{{ __('Contraseña actual') }}</label>
            <input
                wire:model="current_password"
                id="current_password"
                type="password"
                required
                autocomplete="current-password"
                class="app-input {{ $errors->has('current_password') ? 'has-error' : '' }}"
            >
            @error('current_password')
                <p class="app-field-error">{{ $message }}</p>
            @enderror
        </div>

        <div class="app-field">
            <label for="password" class="app-field-label">{{ __('Nueva contraseña') }}</label>
            <input
                wire:model="password"
                id="password"
                type="password"
                required
                autocomplete="new-password"
                class="app-input {{ $errors->has('password') ? 'has-error' : '' }}"
            >
            @error('password')
                <p class="app-field-error">{{ $message }}</p>
            @enderror
        </div>

        <div class="app-field">
            <label for="password_confirmation" class="app-field-label">{{ __('Confirmar contraseña') }}</label>
            <input
                wire:model="password_confirmation"
                id="password_confirmation"
                type="password"
                required
                autocomplete="new-password"
                class="app-input {{ $errors->has('password_confirmation') ? 'has-error' : '' }}"
            >
            @error('password_confirmation')
                <p class="app-field-error">{{ $message }}</p>
            @enderror
        </div>

        {{-- Botón Guardar: solo aparece si hay cambios sin guardar --}}
        <div class="flex justify-end">
            <button
                type="submit"
                wire:loading.attr="disabled"
                wire:dirty
                wire:target="current_password,password,password_confirmation"
                class="btn-primary">
                {{ __('Guardar') }}
            </button>
        </div>

    </form>
</div>
