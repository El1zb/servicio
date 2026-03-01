<section class="mt-10 space-y-6">
    {{-- Header --}}
    <div class="rounded-xl p-6 shadow-sm" style="background-color: var(--index-card-bg);">
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
            style="background-color: var(--period-detail-btn-reject-bg); color: var(--period-detail-btn-reject-text); border: 1px solid var(--period-detail-btn-reject-bg);"
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
              style="background-color: var(--index-card-bg);">

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
                style="background-color: var(--index-card-bg); color: var(--index-text-primary); border: 1px solid var(--index-border);"
            />

            {{-- Footer Modal --}}
            <div class="flex justify-end gap-2 rtl:gap-2 mt-4">
                <flux:modal.close>
                    <flux:button 
                        type="button"
                        class="w-full sm:w-auto px-4 py-2 rounded-lg text-sm transition-all duration-200 hover:-translate-y-0.5"
                        style="color: var(--period-detail-text-secondary); background-color: transparent; border: 1px solid transparent;"
                        onmouseover="this.style.color='var(--period-detail-text-primary)'; this.style.backgroundColor='var(--period-detail-border)'"
                        onmouseout="this.style.color='var(--period-detail-text-secondary)'; this.style.backgroundColor='transparent'"
                    >
                        {{ __('Cancelar') }}
                    </flux:button>
                </flux:modal.close>

                <flux:button 
                    type="submit"
                    class="w-full sm:w-auto px-5 py-2 rounded-lg text-sm font-semibold transition-all duration-200 hover:-translate-y-0.5 hover:shadow-lg flex items-center justify-center gap-2"
                    style="background-color: var(--period-detail-btn-reject-bg); color: var(--period-detail-btn-reject-text); border: 1px solid var(--period-detail-btn-reject-bg);"
                    onmouseover="this.style.opacity='0.85'"
                    onmouseout="this.style.opacity='1'"
                >
                    {{ __('Eliminar cuenta') }}
                </flux:button>
            </div>
        </form>
    </flux:modal>
</section>
