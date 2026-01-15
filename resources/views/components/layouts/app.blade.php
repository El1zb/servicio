<!-- resources/views/layouts/app/sidebar.blade.php -->

<div class="flex min-h-screen bg-[var(--color-bg-all)] text-[var(--text-section-title)]">
    <!-- 🔹 FontAwesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">

    {{-- Sidebar --}}
    <x-layouts.app.sidebar :title="$title ?? null" class="h-screen">
        {{-- Contenedor principal --}}
        <div class="flex-1 flex flex-col">
            

            {{-- Contenido que ocupa el espacio restante --}}
            <flux:main class="flex-1 overflow-y-auto p-6">
                {{ $slot }}
            </flux:main>
        </div>
    </x-layouts.app.sidebar>

    {{-- 🔔 Toast Notifications (GLOBAL) --}}
    <x-toast-notifications />
</div>
