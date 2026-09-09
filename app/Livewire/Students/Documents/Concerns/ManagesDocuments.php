<?php

namespace App\Livewire\Students\Documents\Concerns;

use App\Models\Document;

trait ManagesDocuments
{
    public string $searchDocuments = '';
    public string $statusFilter    = '';

    /** Pestaña visible del switcher: 'entregas' (documentos que el alumno sube)
     *  o 'informativos' (los que solo publica el admin, de lectura).
     *  null = el alumno todavía no elige, la resuelve getDocumentsData(). */
    public ?string $documentsTab = null;

    public function setDocumentsTab(string $tab): void
    {
        $this->documentsTab = $tab === 'informativos' ? 'informativos' : 'entregas';
    }

    public array $statusFilterLabels = [
        ''            => 'Todos los estados',
        'pendiente'   => 'Por entregar',
        'en_revision' => 'En revisión',
        'aprobado'    => 'Aprobados',
        'rechazado'   => 'Rechazados',
        'vencido'     => 'Vencidos',
    ];

    // ─── Query de render ─────────────────────────────────────────────────────────

    protected function getDocumentsData(): array
    {
        $stats = [
            'por_vencer'  => 0,
            'rechazados'  => 0,
            'en_revision' => 0,
            'aprobados'   => 0,
            'vencidos'    => 0,
        ];

        $informativeDocuments = collect();
        $submissionDocuments  = collect();

        if ($this->student && $this->student->status === 'aprobado') {
            $all = Document::where('student_id', $this->student->id)
                ->with(['file', 'file.studentUploads' => fn($q) => $q->where('student_id', $this->student->id)])
                ->where('is_active', true)
                ->get();

            $search = trim($this->searchDocuments);

            $informativeDocuments = $all
                ->filter(fn($d) => $d->file?->upload_mode === 'admin_only')
                ->when($search !== '', fn($c) => $c->filter(
                    fn($d) => str_contains(strtolower($d->name), strtolower($search))
                ))
                ->values();

            $submissionItems = $all
                ->filter(fn($d) => $d->file?->upload_mode !== 'admin_only')
                ->map(function ($doc) {
                    $limitDate = $doc->effectiveLimitDate();

                    return [
                        'document'  => $doc,
                        'limitDate' => $limitDate,
                        'isExpired' => $limitDate && now()->gt($limitDate->copy()->endOfDay()),
                        'hasFile'   => (bool) $doc->student_file_name,
                    ];
                });

            // Estadísticas sobre el conjunto completo (sin aplicar búsqueda/filtro)
            foreach ($submissionItems as $item) {
                $doc = $item['document'];

                if ($doc->status === 'revisado') {
                    $stats['aprobados']++;
                    continue;
                }

                if ($doc->status === 'rechazado')                          $stats['rechazados']++;
                if ($doc->status === 'en_revision' && $item['hasFile'])    $stats['en_revision']++;

                if (! $item['limitDate']) continue;
                if ($item['hasFile'] && $doc->status !== 'rechazado') continue;

                if ($item['isExpired']) {
                    $stats['vencidos']++;
                } elseif (now()->diffInDays($item['limitDate'], false) <= 7) {
                    $stats['por_vencer']++;
                }
            }

            $submissionDocuments = $submissionItems
                ->when($search !== '', fn($c) => $c->filter(
                    fn($item) => str_contains(strtolower($item['document']->name), strtolower($search))
                ))
                ->filter(function ($item) {
                    if ($this->statusFilter === '') return true;

                    $doc = $item['document'];

                    return match ($this->statusFilter) {
                        'pendiente'   => ! $item['hasFile'] && ! $item['isExpired'] && $doc->status !== 'rechazado',
                        'en_revision' => $doc->status === 'en_revision' && $item['hasFile'],
                        'aprobado'    => $doc->status === 'revisado',
                        'rechazado'   => $doc->status === 'rechazado',
                        'vencido'     => $item['isExpired'] && ! $item['hasFile'],
                        default       => true,
                    };
                })
                ->sortBy(function ($item) {
                    $doc = $item['document'];

                    $group = match (true) {
                        $doc->status === 'rechazado'                        => 0,
                        ! $item['hasFile'] && ! $item['isExpired']          => 1,
                        $doc->status === 'en_revision' && $item['hasFile']  => 2,
                        $item['isExpired'] && ! $item['hasFile']            => 3,
                        $doc->status === 'revisado'                         => 4,
                        default                                            => 5,
                    };

                    $timestamp = $item['limitDate'] ? $item['limitDate']->timestamp : PHP_INT_MAX;

                    return sprintf('%d-%020d', $group, $timestamp);
                })
                ->values();

            // Pestaña inicial: si el periodo solo trae informativos, no tiene caso
            // abrir en una pestaña de entregas que siempre estará vacía. Solo
            // aplica mientras el alumno no haya elegido (setDocumentsTab).
            $this->documentsTab ??= $submissionItems->isEmpty() && $informativeDocuments->isNotEmpty()
                ? 'informativos'
                : 'entregas';
        }

        $this->documentsTab ??= 'entregas';

        return compact('informativeDocuments', 'submissionDocuments', 'stats');
    }

    // ─── Helpers de presentación ─────────────────────────────────────────────────

    public function formatSize(int $bytes): string
    {
        if ($bytes >= 1024 * 1024 * 1024) return round($bytes / (1024 * 1024 * 1024), 2) . ' GB';
        if ($bytes >= 1024 * 1024)        return round($bytes / (1024 * 1024), 2) . ' MB';
        if ($bytes >= 1024)               return round($bytes / 1024, 2) . ' KB';
        return $bytes . ' B';
    }
}
