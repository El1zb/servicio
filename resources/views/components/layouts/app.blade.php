    {{-- Sidebar (izquierda) --}}
    <x-layouts.app.sidebar :title="$title ?? null">
        {{ $slot }}
    </x-layouts.app.sidebar>

    {{-- Toast Notifications (global) --}}
    <x-toast-notifications />