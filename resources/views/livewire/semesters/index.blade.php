<div class="space-y-8 min-h-screen p-6">

    @include('livewire.semesters.index.partials.header')

    {{-- Mensaje de sesión --}}
    @if (session()->has('message'))
        <div class="rounded-xl p-4 border flex items-center gap-3
                    bg-green-500/10 border-green-500/30">
            <div class="flex-shrink-0">
                <svg class="w-5 h-5 text-green-400"
                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <p class="text-sm font-medium text-green-400">
                {{ session('message') }}
            </p>
        </div>
    @endif

    <div class="rounded-xl shadow-sm bg-[var(--color-card-bg)]">

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