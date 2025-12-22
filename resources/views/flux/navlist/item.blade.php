@php $iconTrailing ??= $attributes->pluck('icon:trailing'); @endphp
@php $iconVariant ??= $attributes->pluck('icon:variant'); @endphp

@aware([ 'variant' ])

@props([
    'iconVariant' => 'outline',
    'iconTrailing' => null,
    'badgeColor' => null,
    'variant' => null,
    'iconDot' => null,
    'accent' => true,
    'badge' => null,
    'icon' => null,
])

@php
// Button should be a square if it has no text contents...
$square ??= $slot->isEmpty();

// Size-up icons in square/icon-only buttons...
$iconClasses = Flux::classes($square ? 'size-5!' : 'size-4!');

$classes = Flux::classes()
    ->add('h-10 lg:h-8 relative flex items-center gap-3 rounded-lg')
    ->add($square ? 'px-2.5!' : '')
    ->add('py-0 text-start w-full px-3 my-px')
    ->add('text-(--sidebar-item-text-muted) dark:text-(--sidebar-item-text-muted)')
    ->add(match ($variant) {
        'outline' => match ($accent) {
            true => [
                'data-current:text-(--sidebar-item-text-active) hover:data-current:text-(--sidebar-item-text-hover)',
                'data-current:bg-(--sidebar-item-bg-active) dark:data-current:bg-(--sidebar-item-bg-active) data-current:border data-current:border-(--sidebar-border)',
                'hover:text-(--sidebar-item-text-hover) dark:hover:text-(--sidebar-item-text-hover) dark:hover:bg-(--sidebar-item-bg-hover) hover:bg-(--sidebar-item-bg-hover)',
                'border border-transparent',
            ],
            false => [
                'data-current:text-(--sidebar-item-text-muted) dark:data-current:text-(--sidebar-item-text-muted) data-current:border-(--sidebar-border)',
                'data-current:bg-(--sidebar-item-bg-soft) dark:data-current:bg-(--sidebar-item-bg-soft) data-current:border data-current:border-(--sidebar-border) dark:data-current:border-(--sidebar-border) data-current:shadow-xs',
                'hover:text-(--sidebar-item-text-hover) dark:hover:text-(--sidebar-item-text-hover)',
            ],
        },
        default => match ($accent) {
            true => [
                'data-current:text-(--sidebar-item-text-active) hover:data-current:text-(--sidebar-item-text-active)',
                'data-current:bg-(--sidebar-item-bg-active) dark:data-current:bg-(--sidebar-item-bg-active)',
                'hover:text-(--sidebar-item-text-hover) dark:hover:text-(--sidebar-item-text-hover) hover:bg-(--sidebar-item-bg-hover) dark:hover:bg-(--sidebar-item-bg-hover)',
            ],
            false => [
                'data-current:text-(--sidebar-item-text-muted) dark:data-current:text-(--sidebar-item-text-muted)',
                'data-current:bg-(--sidebar-item-bg-active) dark:data-current:bg-(--sidebar-item-bg-active)',
                'hover:text-(--sidebar-item-text-hover) dark:hover:text-(--sidebar-item-text-hover) hover:bg-(--sidebar-item-bg-hover) dark:hover:bg-(--sidebar-item-bg-hover)',
            ],
        },
    })
    ;
@endphp

<flux:button-or-link :attributes="$attributes->class($classes)" data-flux-navlist-item>
    <?php if ($icon): ?>
        <div class="relative">
            <?php if (is_string($icon) && $icon !== ''): ?>
                <flux:icon :$icon :variant="$iconVariant" class="{!! $iconClasses !!}" />
            <?php else: ?>
                {{ $icon }}
            <?php endif; ?>

            <?php if ($iconDot): ?>
                <div class="absolute top-[-2px] end-[-2px]">
                    <div class="size-[6px] rounded-full bg-zinc-500 dark:bg-zinc-400"></div>
                </div>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <?php if ($slot->isNotEmpty()): ?>
        <div class="flex-1 text-sm font-medium leading-none whitespace-nowrap [[data-nav-footer]_&]:hidden [[data-nav-sidebar]_[data-nav-footer]_&]:block" data-content>{{ $slot }}</div>
    <?php endif; ?>

    <?php if (is_string($iconTrailing) && $iconTrailing !== ''): ?>
        <flux:icon :icon="$iconTrailing" :variant="$iconVariant" class="size-4!" />
    <?php elseif ($iconTrailing): ?>
        {{ $iconTrailing }}
    <?php endif; ?>

    <?php if (isset($badge) && $badge !== ''): ?>
        <flux:navlist.badge :attributes="Flux::attributesAfter('badge:', $attributes, ['color' => $badgeColor])">{{ $badge }}</flux:navlist.badge>
    <?php endif; ?>
</flux:button-or-link>
