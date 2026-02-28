@props([
    'title',
    'description' => null,
    'center' => true,
])

<div @class([
    'flex w-full flex-col gap-1',
    'text-center' => $center,
    'text-left' => ! $center,
])>
    <flux:heading
        size="xl"
        class="font-extrabold text-[var(--color-text-header-primary)]"
    >
        {{ $title }}
    </flux:heading>

    @if($description)
        <flux:subheading
            class="text-[var(--color-text-header-secondary)]"
        >
            {{ $description }}
        </flux:subheading>
    @endif
</div>
