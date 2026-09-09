@push('topbar-switcher')
    <x-settings-switcher />
@endpush

<div class="p-6">
    {{-- Mobile: el switcher ya vive en la topbar (app-topbar-mobile-left,
         ver sidebar.blade.php) en lugar del título. Aquí abajo: nombre de
         sección + descripción (en desktop viven en main-content-header y
         más abajo respectivamente); acción opcional (p.ej. botón "+") a la
         derecha, en la misma fila. --}}
    @if($heading ?? false)
        <div class="lg:hidden settings-mobile-header mb-6">
            <div class="min-w-0">
                <h2 class="settings-mobile-title">{{ $heading }}</h2>
                @if($subheading ?? false)
                    <p class="settings-mobile-subheading">{{ $subheading }}</p>
                @endif
            </div>

            @isset($mobileAction)
                <div class="flex-shrink-0">{{ $mobileAction }}</div>
            @endisset
        </div>
    @endif

    <div class="w-full">
        <flux:subheading class="hidden lg:block text-[var(--color-secondary)]">{{ $subheading ?? '' }}</flux:subheading>

        <flux:separator variant="subtle" class="my-6" />

        {{ $slot }}
    </div>
</div>
