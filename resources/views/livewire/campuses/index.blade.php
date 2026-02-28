<div class="space-y-8 min-h-screen p-6">

    @include('livewire.campuses.index.partials.header')

    {{-- Mensaje de sesión --}}
    @if (session()->has('message'))
        <div class="rounded-xl p-4 shadow-lg border flex items-center gap-3"
             style="background-color: var(--index-status-approved-bg); border-color: var(--index-status-approved-icon);">
            <div class="flex-shrink-0">
                <svg class="w-5 h-5" style="color: var(--index-status-approved-icon);"
                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <p class="text-sm font-medium" style="color: var(--index-status-approved-icon);">
                {{ session('message') }}
            </p>
        </div>
    @endif

    <div class="rounded-xl p-5 shadow-lg" style="background-color: var(--index-bg);">

        @include('livewire.campuses.index.partials.search')

        @if($campuses->count())
            @include('livewire.campuses.index.partials.campus-cards')
        @else
            @include('livewire.campuses.index.partials.empty-state')
        @endif

    </div>

    @include('livewire.campuses.index.modals.campus-modal')
    @include('livewire.campuses.index.modals.delete-modal')

</div>