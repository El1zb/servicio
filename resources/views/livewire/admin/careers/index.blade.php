<div class="space-y-8 p-6">

    @include('livewire.admin.careers.index.partials.header')

    <div>

        @include('livewire.admin.careers.index.partials.search')

        @if($careers->count())
            @include('livewire.admin.careers.index.partials.career-cards')
        @else
            @include('livewire.admin.careers.index.partials.empty-state')
        @endif

    </div>

    @include('livewire.admin.careers.index.modals.career-modal')
    @include('livewire.admin.careers.index.modals.delete-modal')

</div>