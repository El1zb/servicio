{{-- Sidebar: Panel de Control --}}
@php
    $upcomingDocs  = [];
    $expiredDocs   = [];

    foreach($documents as $limitDate => $docs) {
        if($limitDate !== 'Sin fecha') {
            $date     = \Carbon\Carbon::parse($limitDate);
            $daysLeft = now()->diffInDays($date, false);

            foreach($docs as $doc) {
                if($doc->status !== 'revisado') {
                    if($daysLeft < 0)       $expiredDocs[]  = ['doc' => $doc, 'date' => $date, 'days' => abs($daysLeft)];
                    elseif($daysLeft <= 7)  $upcomingDocs[] = ['doc' => $doc, 'date' => $date, 'days' => $daysLeft];
                }
            }
        }
    }
@endphp

<div class="lg:col-span-4">
    <div class="lg:sticky lg:top-6 bg-[var(--index-card-bg)] rounded-2xl shadow-xl overflow-hidden">

        <div class="bg-gradient-to-r from-[var(--index-content-bg)] to-[var(--index-content-bg)] p-6">
            <h3 class="text-white font-bold text-lg">Panel de Control</h3>
            <p class="text-white/70 text-xs">Seguimiento de entregas</p>
        </div>

        <div class="sidebar-scroll overflow-y-auto" style="max-height: calc(100vh - 200px);">
            <div class="p-6 space-y-6">
                @include('livewire.students.documents.partials.sidebar.calendar')
                @include('livewire.students.documents.partials.sidebar.summary', [
                    'upcomingDocs'  => $upcomingDocs,
                    'rejectedCount' => $rejectedCount ?? 0,
                    'reviewCount'   => $reviewCount   ?? 0,
                    'approvedCount' => $approvedCount ?? 0,
                ])
            </div>
        </div>

    </div>
</div>