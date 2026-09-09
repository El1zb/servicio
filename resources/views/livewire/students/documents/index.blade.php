<div>
    <div class="max-w-[1600px] mx-auto p-6">

        @if(!$student)
            @include('livewire.students.documents.partials.status.no-profile')

        @elseif($student->status === 'pendiente')
            @include('livewire.students.documents.partials.status.pending')

        @elseif($student->status === 'rechazado')
            @include('livewire.students.documents.partials.status.rejected')

        @else
            {{-- Buscador y filtro de estado viven en el topbar, fuera del componente:
                 se empujan siempre porque los @push solo corren en la carga inicial
                 de la página, no en los re-render de Livewire al cambiar de pestaña. --}}
            @include('livewire.students.documents.partials.topbar-controls')

            @php
                $documentTabs = [
                    [
                        'label'  => 'Entregas',
                        'count'  => $submissionDocuments->count(),
                        'active' => $documentsTab === 'entregas',
                        'click'  => "setDocumentsTab('entregas')",
                    ],
                    [
                        'label'  => 'Informativos',
                        'count'  => $informativeDocuments->count(),
                        'active' => $documentsTab === 'informativos',
                        'click'  => "setDocumentsTab('informativos')",
                    ],
                ];
            @endphp

            <div class="space-y-6">
                @include('livewire.students.documents.partials.stats-bar')

                <x-switcher :options="$documentTabs" />

                @if($documentsTab === 'informativos')
                    @include('livewire.students.documents.partials.informative-documents')
                @else
                    @include('livewire.students.documents.partials.submission-documents')
                @endif
            </div>
        @endif

    </div>

    @include('livewire.students.documents.partials.modals.document-viewer')
    @include('livewire.students.documents.partials.modals.profile-modal')
    @include('livewire.students.documents.partials.modals.upload-modal')
    @include('livewire.students.documents.partials.modals.cancel-upload-modal')
</div>
