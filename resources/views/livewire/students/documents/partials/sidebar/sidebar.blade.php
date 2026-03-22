{{-- Sidebar: Panel de Control --}}
@php
    $upcomingDocs  = [];
    $expiredDocs   = [];
    $rejectedCount = 0;
    $reviewCount   = 0;
    $approvedCount = 0;

    foreach ($documents as $limitDate => $docs) {
        foreach ($docs as $doc) {
            // Contadores globales
            if ($doc->status === 'revisado')                                        $approvedCount++;
            if ($doc->status === 'rechazado')                                       $rejectedCount++;
            if ($doc->status === 'en_revision' && !empty($doc->student_file_name))  $reviewCount++;

            // Próximos a vencer / vencidos
            if ($limitDate === 'Sin fecha') continue;

            $date     = \Carbon\Carbon::parse($limitDate);
            $daysLeft = now()->diffInDays($date, false);

            if ($doc->status === 'revisado') continue;
            if (!empty($doc->student_file_name) && $doc->status !== 'rechazado') continue;

            if ($daysLeft < 0)      $expiredDocs[]  = ['doc' => $doc, 'date' => $date, 'days' => abs($daysLeft)];
            elseif ($daysLeft <= 7) $upcomingDocs[] = ['doc' => $doc, 'date' => $date, 'days' => $daysLeft];
        }
    }
@endphp

<aside class="col-span-1 xl:col-span-3">
    <div class="hidden xl:block fixed top-0 w-[inherit] h-screen border-l-4 border-[var(--color-border)] pointer-events-none"></div>

    <div class="xl:sticky xl:top-6 xl:max-h-[calc(100vh-3rem)] xl:overflow-y-auto sidebar-scroll px-6 py-6 border-t border-[var(--color-border)] xl:border-t-0">

        <div class="mb-5 px-1">
            <h3 class="text-[var(--color-primary)] text-lg" style="font-weight: var(--font-weight-medium);">Panel de Control</h3>
            <p class="text-[var(--color-secondary)] text-sm">Seguimiento de entregas</p>
        </div>

        {{-- En móvil/tablet: 2 columnas. En xl: columna normal --}}
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-1 gap-4 xl:gap-0 xl:space-y-6 pt-4 xl:pt-6 pr-0 xl:pr-3">
            @include('livewire.students.documents.partials.sidebar.calendar')
            @include('livewire.students.documents.partials.sidebar.summary', [
                'upcomingDocs'  => $upcomingDocs,
                'rejectedCount' => $rejectedCount ?? 0,
                'reviewCount'   => $reviewCount   ?? 0,
                'approvedCount' => $approvedCount ?? 0,
            ])
        </div>
    </div>
</aside>