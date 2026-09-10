<section class="w-full">
    <x-settings.layout heading="General" subheading="Administra tu perfil, tu contraseña y la apariencia de tu cuenta.">
        <div class="divide-y divide-[var(--color-border)]">

            {{-- Perfil --}}
            <div class="flex flex-col lg:flex-row gap-6 py-8 first:pt-0">
                <div class="lg:w-64 flex-shrink-0">
                    <h3 class="text-sm font-semibold" style="color: var(--color-primary-2);">Perfil</h3>
                    <p class="text-sm mt-1" style="color: var(--color-secondary);">
                        Actualiza tu nombre y dirección de correo electrónico.
                    </p>
                </div>

                <div class="flex-1 max-w-lg">
                    <form wire:submit.prevent="updateProfileInformation" class="space-y-6">

                        {{-- Nombre --}}
                        <div class="app-field">
                            <label for="name" class="app-field-label">{{ __('Nombre') }}</label>
                            <input
                                wire:model="name"
                                id="name"
                                type="text"
                                required
                                autocomplete="name"
                                class="app-input {{ $errors->has('name') ? 'has-error' : '' }}"
                            >
                            @error('name')
                                <p class="app-field-error">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Correo --}}
                        <div class="app-field">
                            <label for="email" class="app-field-label">{{ __('Correo electrónico') }}</label>
                            <input
                                wire:model="email"
                                id="email"
                                type="email"
                                required
                                autocomplete="email"
                                class="app-input {{ $errors->has('email') ? 'has-error' : '' }}"
                            >
                            @error('email')
                                <p class="app-field-error">{{ $message }}</p>
                            @enderror

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

                        {{-- Botón Guardar: solo aparece si hay cambios sin guardar --}}
                        <div class="flex justify-end">
                            <button
                                type="submit"
                                wire:loading.attr="disabled"
                                wire:dirty
                                wire:target="name,email"
                                class="btn-primary">
                                {{ __('Guardar') }}
                            </button>
                        </div>

                    </form>
                </div>
            </div>

            {{-- Contraseña --}}
            <div class="flex flex-col lg:flex-row gap-6 py-8">
                <div class="lg:w-64 flex-shrink-0">
                    <h3 class="text-sm font-semibold" style="color: var(--color-primary-2);">Contraseña</h3>
                    <p class="text-sm mt-1" style="color: var(--color-secondary);">
                        Asegúrate de que tu cuenta use una contraseña larga y aleatoria para mantenerse segura.
                    </p>
                </div>

                <div class="flex-1 max-w-lg">
                    <livewire:settings.password />
                </div>
            </div>

            {{-- Apariencia --}}
            <div class="flex flex-col lg:flex-row gap-6 py-8">
                <div class="lg:w-64 flex-shrink-0">
                    <h3 class="text-sm font-semibold" style="color: var(--color-primary-2);">Apariencia</h3>
                    <p class="text-sm mt-1" style="color: var(--color-secondary);">
                        Actualiza la configuración de apariencia de tu cuenta.
                    </p>
                </div>

                <div class="flex-1 max-w-lg">
                    <div class="appearance-options" x-data>

                        <button type="button" class="appearance-option" :class="{ 'is-active': $flux.appearance === 'light' }" @click="$flux.appearance = 'light'">
                            <div class="appearance-window">
                                <div class="appearance-window-dots">
                                    <span class="appearance-window-dot"></span>
                                    <span class="appearance-window-dot"></span>
                                    <span class="appearance-window-dot"></span>
                                </div>
                                <div class="appearance-window-preview appearance-window-preview--light">
                                    <div class="appearance-window-toolbar"></div>
                                    <div class="appearance-window-body">
                                        <div class="appearance-window-block"></div>
                                        <div class="appearance-window-block"></div>
                                        <div class="appearance-window-block"></div>
                                        <div class="appearance-window-block"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="appearance-option-label">
                                <span class="appearance-option-check">
                                    <svg width="9" height="9" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3.5" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6L9 17l-5-5"/></svg>
                                </span>
                                {{ __('Claro') }}
                            </div>
                        </button>

                        <button type="button" class="appearance-option" :class="{ 'is-active': $flux.appearance === 'dark' }" @click="$flux.appearance = 'dark'">
                            <div class="appearance-window">
                                <div class="appearance-window-dots">
                                    <span class="appearance-window-dot"></span>
                                    <span class="appearance-window-dot"></span>
                                    <span class="appearance-window-dot"></span>
                                </div>
                                <div class="appearance-window-preview appearance-window-preview--dark">
                                    <div class="appearance-window-toolbar"></div>
                                    <div class="appearance-window-body">
                                        <div class="appearance-window-block"></div>
                                        <div class="appearance-window-block"></div>
                                        <div class="appearance-window-block"></div>
                                        <div class="appearance-window-block"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="appearance-option-label">
                                <span class="appearance-option-check">
                                    <svg width="9" height="9" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3.5" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6L9 17l-5-5"/></svg>
                                </span>
                                {{ __('Oscuro') }}
                            </div>
                        </button>

                        <button type="button" class="appearance-option" :class="{ 'is-active': $flux.appearance === 'system' }" @click="$flux.appearance = 'system'">
                            <div class="appearance-window">
                                <div class="appearance-window-dots">
                                    <span class="appearance-window-dot"></span>
                                    <span class="appearance-window-dot"></span>
                                    <span class="appearance-window-dot"></span>
                                </div>
                                <div class="appearance-window-preview appearance-window-preview--system">
                                    <div class="appearance-window-half">
                                        <div class="appearance-window-toolbar"></div>
                                        <div class="appearance-window-body appearance-window-body--single">
                                            <div class="appearance-window-block"></div>
                                            <div class="appearance-window-block"></div>
                                        </div>
                                    </div>
                                    <div class="appearance-window-half appearance-window-half--dark">
                                        <div class="appearance-window-toolbar"></div>
                                        <div class="appearance-window-body appearance-window-body--single">
                                            <div class="appearance-window-block"></div>
                                            <div class="appearance-window-block"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="appearance-option-label">
                                <span class="appearance-option-check">
                                    <svg width="9" height="9" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3.5" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6L9 17l-5-5"/></svg>
                                </span>
                                {{ __('Sistema') }}
                            </div>
                        </button>

                    </div>
                </div>
            </div>

            {{-- Eliminar cuenta: siempre al final --}}
            <div class="flex flex-col lg:flex-row gap-6 py-8 last:pb-0">
                <div class="lg:w-64 flex-shrink-0">
                    <h3 class="text-sm font-semibold" style="color: #DC2626;">Eliminar cuenta</h3>
                    <p class="text-sm mt-1" style="color: var(--color-secondary);">
                        Elimina tu cuenta y todos los recursos asociados. Esta acción no se puede deshacer.
                    </p>
                </div>

                <div class="flex-1 max-w-lg">
                    <livewire:settings.delete-user-form />
                </div>
            </div>

        </div>
    </x-settings.layout>
</section>
