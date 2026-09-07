<!-- 🔹 FontAwesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">

    {{-- Sidebar (izquierda) --}}
    <x-layouts.app.sidebar :title="$title ?? null">
        {{ $slot }}
    </x-layouts.app.sidebar>

    {{-- 🔔 Toast Notifications (GLOBAL) --}}
    <x-toast-notifications />