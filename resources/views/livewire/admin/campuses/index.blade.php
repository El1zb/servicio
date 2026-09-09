<div class="space-y-8 p-6">

    @include('livewire.admin.campuses.index.partials.header')

    <div>

        @include('livewire.admin.campuses.index.partials.search')

        @if($campuses->count())
            @include('livewire.admin.campuses.index.partials.campus-cards')
        @else
            @include('livewire.admin.campuses.index.partials.empty-state')
        @endif

    </div>

    @include('livewire.admin.campuses.index.modals.campus-modal')
    @include('livewire.admin.campuses.index.modals.delete-modal')

</div>