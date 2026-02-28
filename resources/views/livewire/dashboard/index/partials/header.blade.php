<div class="space-y-8 min-h-screen p-6">

    @include('livewire.dashboard.index.partials.header')

    @include('livewire.dashboard.index.partials.stats-bar', ['stats' => $stats])

    <div class="rounded-xl p-5 shadow-lg" style="background-color: var(--index-bg);">

        @include('livewire.dashboard.index.partials.filters')

        @if($periods->count())
            @include('livewire.dashboard.index.partials.period-grid', ['periods' => $periods])
        @else
            @include('livewire.dashboard.index.partials.empty-state')
        @endif

    </div>

    @include('livewire.dashboard.index.modals.period-modal', ['semesters' => $semesters])
    @include('livewire.dashboard.index.modals.delete-modal')

</div>