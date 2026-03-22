<div class="flex items-start max-md:flex-col">
    <div class="me-10 w-full pb-4 md:w-[220px] md:sticky md:top-6">
        <flux:navlist>
            <flux:navlist.item
                :href="route('settings.profile')"
                wire:navigate
                style="color: var(--color-primary-2);"
                class="hover:text-[var(--color-primary)] hover:bg-[var(--color-icon-bg)]!"
            >
                {{ __('Perfil') }}
            </flux:navlist.item>

            <flux:navlist.item :href="route('settings.password')" wire:navigate
                style="color: var(--color-primary-2);"
                class="hover:text-[var(--color-primary)] hover:bg-[var(--color-icon-bg)]!"
            >
                {{ __('Contraseña') }}
            </flux:navlist.item>

            <flux:navlist.item :href="route('settings.appearance')" wire:navigate
                style="color: var(--color-primary-2);"
                class="hover:text-[var(--color-primary)] hover:bg-[var(--color-icon-bg)]!"
            >
                {{ __('Apariencia') }}
            </flux:navlist.item>

            @if(auth()->user()->hasRole('admin'))
                <flux:navlist.item
                    :href="route('admin.create-admin')"
                    wire:navigate
                    style="color: var(--color-primary-2);"
                    class="hover:text-[var(--color-primary)] hover:bg-[var(--color-icon-bg)]!"
                >
                    {{ __('Administradores') }}
                </flux:navlist.item>
            @endif
        </flux:navlist>
    </div>

    <flux:separator class="md:hidden" />

    <div class="flex-1 self-stretch max-md:pt-6">
        <flux:heading>{{ $heading ?? '' }}</flux:heading>
        <flux:subheading>{{ $subheading ?? '' }}</flux:subheading>

        <div class="w-full max-w-3xl">
            {{ $slot }}
        </div>
    </div>
</div>