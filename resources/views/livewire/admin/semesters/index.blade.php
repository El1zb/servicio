<div class="space-y-8 p-6">

    @include('livewire.admin.semesters.index.partials.header')

    <div>

        @include('livewire.admin.semesters.index.partials.search')

        @if($semesters->count())
            @include('livewire.admin.semesters.index.partials.semester-cards')
        @else
            @include('livewire.admin.semesters.index.partials.empty-state')
        @endif

    </div>

    @include('livewire.admin.semesters.index.modals.semester-modal')
    @include('livewire.admin.semesters.index.modals.delete-modal')

</div>