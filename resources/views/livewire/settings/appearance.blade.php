<section class="w-full">
    @include('partials.settings-heading')

    <x-settings.layout>
        <div class="space-y-6">

            {{-- Header --}}
            <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-6 rounded-xl shadow-sm p-6"
                 style="background-color: var(--color-card-bg);">
                <div>
                    <x-auth-header
                        title="Apariencia"
                        description="Actualiza la configuración de apariencia de tu cuenta."
                        :center="false"
                    />
                </div>
            </div>

            {{-- Contenido Apariencia --}}
            <div class="rounded-xl p-6 shadow-lg" style="background-color: var(--color-card-bg);">
                <flux:radio.group x-data variant="segmented" x-model="$flux.appearance" style="background-color: var(--color-icon-bg); border: 1px solid var(--color-border-hover);">
                    <flux:radio value="light" icon="sun">{{ __('Claro') }}</flux:radio>
                    <flux:radio value="dark" icon="moon">{{ __('Oscuro') }}</flux:radio>
                    <flux:radio value="system" icon="computer-desktop">{{ __('Sistema') }}</flux:radio>
                </flux:radio.group>
            </div>

        </div>
    </x-settings.layout>
</section>