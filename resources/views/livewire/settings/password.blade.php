<section class="w-full">
    @include('partials.settings-heading')

    <x-settings.layout>
        <div class="space-y-6">

            {{-- Header --}}
            <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-6 rounded-xl shadow-sm p-6"
                 style="background-color: var(--color-card-bg);">
                <div>
                    <x-auth-header
                        title="Actualizar contraseña"
                        description="Asegúrate de que tu cuenta use una contraseña larga y aleatoria para mantenerse segura."
                        :center="false"
                    />
                </div>
            </div>

            {{-- Mensaje flash --}}
            @if (session()->has('message'))
                <div class="rounded-xl p-4 shadow-lg border flex items-center gap-3"
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
            <div class="rounded-xl p-6 shadow-lg" style="background-color: var(--color-card-bg);">
                <form method="POST" wire:submit.prevent="updatePassword" class="space-y-6">

                    <flux:input
                        wire:model="current_password"
                        :label="__('Contraseña actual')"
                        type="password"
                        required
                        autocomplete="current-password"
                        class="w-full"
                        style="background-color: var(--color-icon-bg); color: var(--color-primary-2); border: 1px solid var(--color-border-hover);"
                    />

                    <flux:input
                        wire:model="password"
                        :label="__('Nueva contraseña')"
                        type="password"
                        required
                        autocomplete="new-password"
                        class="w-full"
                        style="background-color: var(--color-icon-bg); color: var(--color-primary-2); border: 1px solid var(--color-border-hover);"
                    />

                    <flux:input
                        wire:model="password_confirmation"
                        :label="__('Confirmar contraseña')"
                        type="password"
                        required
                        autocomplete="new-password"
                        class="w-full"
                        style="background-color: var(--color-icon-bg); color: var(--color-primary-2); border: 1px solid var(--color-border-hover);"
                    />

                    {{-- Botón Guardar --}}
                    <div class="flex justify-end">
                        <flux:button
                            type="submit"
                            wire:loading.attr="disabled"
                            class="group relative inline-flex items-center justify-center px-4 py-2
                            rounded-[var(--radius-md)]
                            bg-[var(--color-primary)]! text-[var(--color-bg)]!
                            shadow-lg shadow-[var(--color-border-hover)]
                            hover:bg-[var(--color-primary-2)]! hover:shadow-xl hover:-translate-y-0.5
                            transition-all duration-300 disabled:opacity-60 disabled:cursor-not-allowed gap-2">
                            {{ __('Guardar') }}
                        </flux:button>
                    </div>

                    {{-- Mensaje de acción --}}
                    <x-action-message class="mt-3" on="password-updated">
                        {{ __('Guardado.') }}
                    </x-action-message>

                </form>
            </div>

        </div>
    </x-settings.layout>
</section>