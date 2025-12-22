<section class="mt-10 space-y-6">
    <div class="relative mb-5">
        <flux:heading>{{ __('Eliminar cuenta') }}</flux:heading>
        <flux:subheading>{{ __('Elimina tu cuenta y todos los recursos asociados') }}</flux:subheading>
    </div>

    <flux:modal.trigger name="confirm-user-deletion">
        <flux:button 
            x-data="" 
            x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
            type="button"
            class="
                group relative w-full h-12
                max-w-[140px]
                rounded-[var(--radius-md)]
                bg-[var(--settings-btn-danger)]!
                text-[var(--settings-btn-danger-text)]!
                shadow-lg shadow-[var(--settings-btn-danger-shadow)]
                hover:bg-[var(--settings-btn-danger-hover)]!
                hover:shadow-xl hover:-translate-y-0.5
                transition-all duration-300
                disabled:opacity-60 disabled:cursor-not-allowed
            "
        >
            {{ __('Eliminar cuenta') }}
        </flux:button>
    </flux:modal.trigger>

    <flux:modal 
        name="confirm-user-deletion" 
        :show="$errors->isNotEmpty()" 
        focusable 
        class="max-w-lg"
    >
        <form method="POST" wire:submit="deleteUser" class="space-y-6">
            <div>
                <flux:heading size="lg">
                    {{ __('¿Estás seguro de que deseas eliminar tu cuenta?') }}
                </flux:heading>

                <flux:subheading>
                    {{ __('Una vez que tu cuenta sea eliminada, todos sus recursos y datos se eliminarán de forma permanente. Por favor, ingresa tu contraseña para confirmar que deseas eliminar tu cuenta de manera definitiva.') }}
                </flux:subheading>
            </div>

            <flux:input 
                wire:model="password" 
                :label="__('Contraseña')" 
                type="password" 
            />

            <div class="flex justify-end space-x-2 rtl:space-x-reverse">
                <flux:modal.close>
                    <flux:button 
                        type="button"
                        class="
                            group relative w-full h-12
                            max-w-[140px]
                            rounded-[var(--radius-md)]
                            bg-[var(--settings-btn-cancel)]!
                            text-[var(--settings-btn-cancel-text)]!
                            shadow-lg shadow-[var(--settings-btn-cancel-shadow)]
                            hover:bg-[var(--settings-btn-cancel-hover)]!
                            hover:shadow-xl hover:-translate-y-0.5
                            transition-all duration-300
                            disabled:opacity-60 disabled:cursor-not-allowed
                        "
                    >
                        {{ __('Cancelar') }}
                    </flux:button>
                </flux:modal.close>

                <flux:button 
                    type="submit"
                    class="
                        group relative w-full h-12
                        max-w-[140px]
                        rounded-[var(--radius-md)]
                        bg-[var(--settings-btn-danger)]!
                        text-[var(--settings-btn-danger-text)]!
                        shadow-lg shadow-[var(--settings-btn-danger-shadow)]
                        hover:bg-[var(--settings-btn-danger-hover)]!
                        hover:shadow-xl hover:-translate-y-0.5
                        transition-all duration-300
                        disabled:opacity-60 disabled:cursor-not-allowed
                    "
                >
                    {{ __('Eliminar cuenta') }}
                </flux:button>
            </div>
        </form>
    </flux:modal>
</section>
