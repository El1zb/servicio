<div class="min-h-screen">
    <div class="max-w-[1600px] mx-auto">

        @if(!$student)
            @include('livewire.students.documents.partials.status.no-profile')

        @elseif($student->status === 'pendiente')
            @include('livewire.students.documents.partials.status.pending')

        @elseif($student->status === 'rechazado')
            @include('livewire.students.documents.partials.status.rejected')

        @else
            <div class="grid grid-cols-1 xl:grid-cols-12">

                {{-- Columna principal --}}
                <div class="xl:col-span-9 space-y-1">
                    @include('livewire.students.documents.partials.header')

                    <div x-data="{
                            openCard: null,
                            toggleCard(date) { this.openCard = this.openCard === date ? null : date; }
                        }"
                        class="rounded-xl p-6">
                        <div class="space-y-4">
                            @include('livewire.students.documents.partials.document-cards-unified')
                        </div>
                    </div>
                </div>

                {{-- Sidebar --}}
                @include('livewire.students.documents.partials.sidebar.sidebar')

            </div>
        @endif

    </div>

    @include('livewire.students.documents.partials.modals.preview-modal')
    @include('livewire.students.documents.partials.modals.comments-modal')
</div>