<div class="space-y-8 min-h-screen p-6">

    @include('livewire.dashboard.period.partials.header')

    @include('livewire.dashboard.period.partials.stats-bar')

    @include('livewire.dashboard.period.partials.tabs-nav')

    {{-- Content Area --}}
    <div class="w-full">
        @if($activeTab === 'estudiantes')
            @include('livewire.dashboard.period.partials.students-tab')
        @endif

        @if($activeTab === 'documentos')
            @include('livewire.dashboard.period.partials.documents-tab')
        @endif

        @if($activeTab === 'revision')
            @include('livewire.dashboard.period.partials.revision-tab')
        @endif
    </div>

    {{-- Modales --}}
    @include('livewire.dashboard.period.modals.student-modal')
    @include('livewire.dashboard.period.modals.reject-modal')
    @include('livewire.dashboard.period.modals.preview-modal')
    @include('livewire.dashboard.period.modals.delete-document-modal')
    @include('livewire.dashboard.period.modals.upload-mode-modal')
    @include('livewire.dashboard.period.modals.quick-review-modal')

</div>