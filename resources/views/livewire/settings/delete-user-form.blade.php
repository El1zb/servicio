<div>
    {{-- Botón Eliminar cuenta --}}
    <flux:modal.trigger name="confirm-user-deletion">
        <button
            x-data=""
            x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
            type="button"
            class="btn-danger"
        >
            {{ __('Eliminar cuenta') }}
        </button>
    </flux:modal.trigger>

    {{-- =================== MODAL ELIMINAR CUENTA =================== --}}
    <flux:modal
        name="confirm-user-deletion"
        :show="$errors->isNotEmpty()"
        :dismissible="false"
        :closable="false"
        focusable
        class="w-[95vw] sm:w-[90vw] md:w-[460px] max-w-[95vw]"
        style="border-color: var(--color-border);"
    >
        <form method="POST" wire:submit="deleteUser" class="flex flex-col" style="max-height: 90vh;">

            {{-- ── Header ── --}}
            <div class="px-6 py-5 flex-shrink-0" style="border-bottom: 1px solid var(--color-border);">
                <h2 class="text-lg font-semibold leading-tight" style="color: var(--color-primary-2);">
                    {{ __('¿Eliminar tu cuenta?') }}
                </h2>
                <p class="text-sm mt-0.5" style="color: var(--color-secondary);">
                    {{ __('Todos tus recursos y datos se eliminarán de forma permanente. Ingresa tu contraseña para confirmar.') }}
                </p>
            </div>

            {{-- ── Body ── --}}
            <div class="flex-1 overflow-y-auto px-6 py-5" style="background-color: var(--color-modal-bg);">
                <div class="app-field">
                    <label for="delete-password" class="app-field-label">{{ __('Contraseña') }}</label>
                    <input
                        wire:model="password"
                        id="delete-password"
                        type="password"
                        autocomplete="current-password"
                        class="app-input w-full"
                    >
                    @error('password')
                        <p class="app-field-error">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- ── Footer ── --}}
            <div class="flex items-center justify-end gap-2 px-6 py-4 flex-shrink-0"
                style="border-top: 1px solid var(--color-border);">

                <flux:modal.close>
                    <button type="button"
                            class="px-4 py-2 rounded-full text-sm transition-colors duration-150 text-[var(--color-secondary)] hover:text-[var(--color-primary-2)] hover:bg-[var(--sidebar-color-hover)]">
                        {{ __('Cancelar') }}
                    </button>
                </flux:modal.close>

                <button type="submit" class="btn-danger">
                    {{ __('Eliminar cuenta') }}
                </button>
            </div>
        </form>
    </flux:modal>
</div>