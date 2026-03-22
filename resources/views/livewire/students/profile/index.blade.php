<div class="flex flex-col gap-6
            p-6 xl:px-12 2xl:px-16
            rounded-2xl
            max-w-7xl w-full mx-auto">

    @include('livewire.students.profile.partials.header')

    @if ($showForm)
        <form method="POST" wire:submit.prevent="save" class="flex flex-col gap-6">

            @include('livewire.students.profile.partials.personal-data')

            @if ($this->canEditAcademic() && $student->status !== 'aprobado')
                @include('livewire.students.profile.partials.academic-data')
            @endif

            @include('livewire.students.profile.partials.save-button')

        </form>
    @else
        <div class="p-6 space-y-4">
            @if ($student->status === 'pendiente')
                @include('livewire.students.profile.partials.status.pending')
            @elseif ($student->status === 'aprobado')
                @include('livewire.students.profile.partials.status.approved')
            @elseif ($student->status === 'rechazado')
                @include('livewire.students.profile.partials.status.rejected')
            @endif
        </div>
    @endif

</div>