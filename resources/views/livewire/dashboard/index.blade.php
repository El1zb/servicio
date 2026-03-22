<div class="space-y-8 min-h-screen p-6">

    @include('livewire.dashboard.index.partials.header')

    @include('livewire.dashboard.index.partials.stats')

    <div class="rounded-xl p-5 shadow-lg" style="background-color: var(--color-card-bg);">

        @include('livewire.dashboard.index.partials.filters')

        @if($periods->count())
            @include('livewire.dashboard.index.partials.period-cards')
        @else
            @include('livewire.dashboard.index.partials.empty-state')
        @endif

    </div>

    @include('livewire.dashboard.index.modals.period-modal')
    @include('livewire.dashboard.index.modals.delete-modal')

</div>