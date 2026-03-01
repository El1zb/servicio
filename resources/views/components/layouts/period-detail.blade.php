{{-- resources/views/components/layouts/period-detail.blade.php --}}
<?php require app_path('Livewire/Dashboard/Period/period-detail-config.php'); ?>

<x-layouts.app>

    <div class="space-y-8 min-h-screen p-6">

        @include('livewire.dashboard.period.partials.header', ['period' => $period])

        @include('livewire.dashboard.period.partials.stats-bar', ['period' => $period])

        @include('livewire.dashboard.period.partials.tabs-nav', ['period' => $period, 'tabs' => $tabs])

        <div class="w-full">
            {{ $slot }}
        </div>

    </div>

</x-layouts.app>