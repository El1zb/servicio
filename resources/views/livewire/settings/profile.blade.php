<section class="w-full">
    @include('partials.settings-heading')

    <x-settings.layout>
        <div class="space-y-6">

            {{-- Header --}}
            <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-6 rounded-xl shadow-sm p-6"
                 style="background-color: var(--color-card-bg);">
                <div>
                    <x-auth-header
                        title="Perfil"
                        description="Actualiza tu nombre y dirección de correo electrónico"
                        :center="false"
                    />
                </div>
            </div>

            {{-- Formulario Perfil --}}
            <div class="rounded-xl p-6 shadow-lg" style="background-color: var(--color-card-bg);">
                <form wire:submit.prevent="updateProfileInformation" class="space-y-6 w-full">

                    {{-- Nombre --}}
                    <flux:input
                        wire:model="name"
                        :label="__('Nombre')"
                        type="text"
                        required
                        autofocus
                        autocomplete="name"
                        class="w-full"
                        style="background-color: var(--color-icon-bg); color: var(--color-primary-2); border: 1px solid var(--color-border-hover);"
                    />

                    {{-- Correo --}}
                    <div>
                        <flux:input
                            wire:model="email"
                            :label="__('Correo electrónico')"
                            type="email"
                            required
                            autocomplete="email"
                            class="w-full"
                            style="background-color: var(--color-icon-bg); color: var(--color-primary-2); border: 1px solid var(--color-border-hover);"
                        />

                        @if (auth()->user() instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! auth()->user()->hasVerifiedEmail())
                            <div>
                                <flux:text class="mt-4">
                                    {{ __('Tu dirección de correo electrónico no está verificada.') }}

                                    <flux:link
                                        class="text-sm cursor-pointer"
                                        wire:click.prevent="resendVerificationNotification"
                                    >
                                        {{ __('Haz clic aquí para reenviar el correo de verificación.') }}
                                    </flux:link>
                                </flux:text>

                                @if (session('status') === 'verification-link-sent')
                                    <flux:text class="mt-2 font-medium !dark:text-green-400 !text-green-600">
                                        {{ __('Se ha enviado un nuevo enlace de verificación a tu correo electrónico.') }}
                                    </flux:text>
                                @endif
                            </div>
                        @endif
                    </div>

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
                    <x-action-message class="mt-3" on="profile-updated">
                        {{ __('Guardado.') }}
                    </x-action-message>

                </form>
            </div>

            {{-- Eliminar usuario --}}
            <livewire:settings.delete-user-form />

        </div>
    </x-settings.layout>
</section>