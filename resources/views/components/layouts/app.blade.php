<!-- 🔹 FontAwesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">

    {{-- Sidebar (izquierda) --}}
    <x-layouts.app.sidebar :title="$title ?? null" class="flex flex-col h-screen flex-shrink-0">
        <flux:main class="flex-1 flex flex-col p-6">
            {{ $slot }}
        </flux:main>
    </x-layouts.app.sidebar>

    {{-- 🔔 Toast Notifications (GLOBAL) --}}
    <x-toast-notifications />