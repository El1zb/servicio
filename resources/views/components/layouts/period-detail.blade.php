{{-- resources/views/components/layouts/period-detail.blade.php --}}
<?php require app_path('Livewire/Dashboard/Period/period-detail-config.php'); ?>

<x-layouts.app :title="$period->name">

    <div class="space-y-8 p-6">

        @include('livewire.dashboard.period.partials.header', ['period' => $period])

        {{-- Las estadísticas viven dentro de cada pestaña (no aquí en el
             layout), porque el layout solo se renderiza una vez: si
             estuvieran aquí, no se actualizarían tras aprobar/rechazar u
             otras acciones de Livewire dentro de la pestaña activa. --}}

        @include('livewire.dashboard.period.partials.tabs-nav', ['period' => $period, 'tabs' => $tabs])

        <div class="w-full">
            {{ $slot }}
        </div>

    </div>

</x-layouts.app>