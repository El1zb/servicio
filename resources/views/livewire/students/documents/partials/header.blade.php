{{-- Header + Progress Ring --}}
@php
    $allDocs      = [];
    foreach($documents as $docs) {
        $allDocs = array_merge($allDocs, $docs->toArray());
    }

    // Solo documentos que requieren acción del estudiante
    $studentDocs    = collect($allDocs)->where('upload_mode', '!=', 'admin_only');
    $totalDocs      = $studentDocs->count();
    $rejectedCount  = $studentDocs->where('status', 'rechazado')->count();
    $approvedCount  = $studentDocs->where('status', 'revisado')->count();
    $reviewCount    = $studentDocs->filter(fn($d) => $d['status'] === 'en_revision' && !empty($d['student_file_path']))->count();
    $deliveredCount = $studentDocs->filter(
        fn($d) => !empty($d['student_file_path']) && $d['status'] !== 'rechazado'
    )->count();
    $progress       = $totalDocs > 0 ? round(($deliveredCount / $totalDocs) * 100) : 0;
@endphp

<div class="rounded-xl shadow-sm p-6">
    <div class="flex items-center justify-between">
        <x-auth-header title="Mis Documentos" description="Gestiona tus entregas académicas" :center="false"/>

        <div class="flex items-center gap-4">
            <div class="text-right">
                <div class="text-2xl font-bold text-[var(--color-text)]">
                    {{ $deliveredCount }}<span class="text-[var(--color-text)]">/{{ $totalDocs }}</span>
                </div>
                <div class="text-[11px] text-[var(--color-secondary)]" style="font-weight: bold;">ENTREGADOS</div>
            </div>
        </div>
    </div>
</div>