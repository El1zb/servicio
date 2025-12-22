<section class="w-full">
    @include('partials.settings-heading')

    <x-settings.layout 
        :heading="__('Actualizar contraseña')" 
        :subheading="__('Asegúrate de que tu cuenta use una contraseña larga y aleatoria para mantenerse segura')"
    >
        <form method="POST" wire:submit="updatePassword" class="mt-6 space-y-6">
            <flux:input
                wire:model="current_password"
                :label="__('Contraseña actual')"
                type="password"
                required
                autocomplete="current-password"
            />

            <flux:input
                wire:model="password"
                :label="__('Nueva contraseña')"
                type="password"
                required
                autocomplete="new-password"
            />

            <flux:input
                wire:model="password_confirmation"
                :label="__('Confirmar contraseña')"
                type="password"
                required
                autocomplete="new-password"
            />

            <div class="flex items-center gap-4">
                <div class="flex items-center justify-end">
                    <flux:button
                        type="submit"
                        wire:loading.attr="disabled"
                        class="
                            group relative w-full h-12
                            max-w-[280px]
                            rounded-[var(--radius-md)]
                            bg-[var(--settings-btn-primary)]!
                            text-[var(--settings-btn-primary-text)]!
                            shadow-lg shadow-[var(--settings-btn-primary-shadow)]
                            hover:bg-[var(--settings-btn-primary-hover)]!
                            hover:shadow-xl hover:-translate-y-0.5
                            transition-all duration-300
                            disabled:opacity-60 disabled:cursor-not-allowed
                        "
                    >
                        {{ __('Guardar') }}
                    </flux:button>

                </div>

                <x-action-message class="me-3" on="password-updated">
                    {{ __('Guardado.') }}
                </x-action-message>
            </div>
        </form>
    </x-settings.layout>
</section>
