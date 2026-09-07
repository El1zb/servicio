<div class="space-y-8 p-6">

    @include('livewire.careers.index.partials.header')

    <div>

        @include('livewire.careers.index.partials.search')

        @if($careers->count())
            @include('livewire.careers.index.partials.career-cards')
        @else
            @include('livewire.careers.index.partials.empty-state')
        @endif

    </div>

    @include('livewire.careers.index.modals.career-modal')
    @include('livewire.careers.index.modals.delete-modal')

</div>