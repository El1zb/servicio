<div>
    <div class="max-w-[1600px] mx-auto p-6">

        @if(!$student)
            @include('livewire.students.documents.partials.status.no-profile')

        @elseif($student->status === 'pendiente')
            @include('livewire.students.documents.partials.status.pending')

        @elseif($student->status === 'rechazado')
            @include('livewire.students.documents.partials.status.rejected')

        @else
            <div class="space-y-6">
                @include('livewire.students.documents.partials.stats-bar')
                @include('livewire.students.documents.partials.informative-documents')
                @include('livewire.students.documents.partials.submission-documents')
            </div>
        @endif

    </div>

    @include('livewire.students.documents.partials.modals.document-viewer')
    @include('livewire.students.documents.partials.modals.profile-modal')
    @include('livewire.students.documents.partials.modals.upload-modal')
    @include('livewire.students.documents.partials.modals.cancel-upload-modal')
</div>
