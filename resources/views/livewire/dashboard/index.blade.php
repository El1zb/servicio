@php
    // Compartidas entre header.blade.php (selects mobile) y filters.blade.php
    // (selects desktop, vía @push('header-filters')).
    $statusFilterLabels = ['all' => 'Todos', 'active' => 'Activos', 'inactive' => 'Inactivos'];
    $sortByLabels = ['recent' => 'Más recientes', 'oldest' => 'Más antiguos', 'name' => 'Nombre (A–Z)'];
@endphp

<div class="space-y-8 p-6">

    @include('livewire.dashboard.index.partials.header')

    @include('livewire.dashboard.index.partials.filters')

    @if($periods->count())
        @include('livewire.dashboard.index.partials.period-cards')
    @else
        @include('livewire.dashboard.index.partials.empty-state')
    @endif

    @include('livewire.dashboard.index.modals.period-modal')
    @include('livewire.dashboard.index.modals.delete-modal')

</div>