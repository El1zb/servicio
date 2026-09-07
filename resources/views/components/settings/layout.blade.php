@push('topbar-switcher')
    <x-settings-switcher />
@endpush

<div class="p-6">
    <div class="lg:hidden mb-6">
        <x-settings-switcher />
    </div>

    <div class="w-full">
        <flux:subheading class="text-[var(--color-secondary)]">{{ $subheading ?? '' }}</flux:subheading>

        <flux:separator variant="subtle" class="my-6" />

        {{ $slot }}
    </div>
</div>
