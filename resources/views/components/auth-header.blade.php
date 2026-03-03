@props([
    'title',
    'description' => null,
    'center' => true,
])

<div @class([
    'flex w-full flex-col gap-0',
    'text-center' => $center,
    'text-left' => ! $center,
])>
    <flux:heading
        size="xl"
        class="font-extrabold text-[var(--color-primary)]"
    >
        {{ $title }}
    </flux:heading>

    @if($description)
        <flux:subheading
            class="text-[var(--color-secondary)]"
        >
            {{ $description }}
        </flux:subheading>
    @endif
</div>
