<section class="mt-10 space-y-6">
    {{-- Header --}}
    <div class="rounded-xl p-6 shadow-sm" style="background-color: var(--color-card-bg);">
        <x-auth-header
            title="{{ __('Eliminar cuenta') }}"
            description="{{ __('Elimina tu cuenta y todos los recursos asociados') }}"
            :center="false"
        />
    </div>

    {{-- Botón Eliminar cuenta --}}
    <flux:modal.trigger name="confirm-user-deletion">
       <flux:button
            x-data=""
            x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
            type="button"
            class="px-4 py-2 rounded-lg text-base font-medium transition-all duration-200 hover:-translate-y-0.5 hover:shadow-md"
            style="background-color: rgba(239, 68, 68, 0.9); color: white; border: 1px solid rgba(239, 68, 68, 0.9);"
            onmouseover="this.style.opacity='0.85'"
            onmouseout="this.style.opacity='1'"
        >
            {{ __('Eliminar cuenta') }}
        </flux:button>
    </flux:modal.trigger>

    {{-- Modal Confirmación --}}
    <flux:modal
        name="confirm-user-deletion"
        :show="$errors->isNotEmpty()"
        focusable
        class="max-w-lg"
    >
        <form method="POST" wire:submit="deleteUser" class="space-y-6 rounded-xl p-6 shadow-lg"
              style="background-color: var(--color-card-bg);">

            {{-- Header Modal --}}
            <div class="space-y-2">
                <flux:heading size="lg">
                    {{ __('¿Estás seguro de que deseas eliminar tu cuenta?') }}
                </flux:heading>
                <flux:subheading>
                    {{ __('Una vez que tu cuenta sea eliminada, todos sus recursos y datos se eliminarán de forma permanente. Por favor, ingresa tu contraseña para confirmar que deseas eliminar tu cuenta de manera definitiva.') }}
                </flux:subheading>
            </div>

            {{-- Input Contraseña --}}
            <flux:input
                wire:model="password"
                :label="__('Contraseña')"
                type="password"
                class="w-full"
                style="background-color: var(--color-icon-bg); color: var(--color-primary-2); border: 1px solid var(--color-border-hover);"
            />

            {{-- Footer Modal --}}
            <div class="flex justify-end gap-2 rtl:gap-2 mt-4">
                <flux:modal.close>
                    <flux:button
                        type="button"
                        class="w-full sm:w-auto px-4 py-2 rounded-lg text-sm transition-all duration-200 hover:-translate-y-0.5"
                        style="color: var(--color-secondary); background-color: transparent; border: 1px solid transparent;"
                        onmouseover="this.style.color='var(--color-primary-2)'; this.style.backgroundColor='var(--color-border-hover)'"
                        onmouseout="this.style.color='var(--color-secondary)'; this.style.backgroundColor='transparent'"
                    >
                        {{ __('Cancelar') }}
                    </flux:button>
                </flux:modal.close>

                <flux:button
                    type="submit"
                    class="w-full sm:w-auto px-5 py-2 rounded-lg text-sm font-semibold transition-all duration-200 hover:-translate-y-0.5 hover:shadow-lg flex items-center justify-center gap-2"
                    style="background-color: rgba(239, 68, 68, 0.9); color: white; border: 1px solid rgba(239, 68, 68, 0.9);"
                    onmouseover="this.style.opacity='0.85'"
                    onmouseout="this.style.opacity='1'"
                >
                    {{ __('Eliminar cuenta') }}
                </flux:button>
            </div>
        </form>
    </flux:modal>
</section>