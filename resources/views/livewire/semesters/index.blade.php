<div class="space-y-8 p-6">

    @include('livewire.semesters.index.partials.header')

    <div>

        @include('livewire.semesters.index.partials.search')

        @if($semesters->count())
            @include('livewire.semesters.index.partials.semester-cards')
        @else
            @include('livewire.semesters.index.partials.empty-state')
        @endif

    </div>

    @include('livewire.semesters.index.modals.semester-modal')
    @include('livewire.semesters.index.modals.delete-modal')

</div>