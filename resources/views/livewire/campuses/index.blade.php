<div class="space-y-8 p-6">

    @include('livewire.campuses.index.partials.header')

    <div>

        @include('livewire.campuses.index.partials.search')

        @if($campuses->count())
            @include('livewire.campuses.index.partials.campus-cards')
        @else
            @include('livewire.campuses.index.partials.empty-state')
        @endif

    </div>

    @include('livewire.campuses.index.modals.campus-modal')
    @include('livewire.campuses.index.modals.delete-modal')

</div>