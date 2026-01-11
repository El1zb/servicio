<!-- resources/views/layouts/app/sidebar.blade.php -->

<div class="flex min-h-screen bg-[var(--color-bg-all)] text-[var(--text-section-title)]">
    <!-- 🔹 FontAwesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">

    <x-layouts.app.sidebar :title="$title ?? null" class="h-screen">
        <flux:main class="flex-1 h-screen overflow-y-auto p-6">
            {{ $slot }}
        </flux:main>
    </x-layouts.app.sidebar>

    
    {{-- 🔔 Toast Notifications (GLOBAL) --}}
    <x-toast-notifications />
</div>
