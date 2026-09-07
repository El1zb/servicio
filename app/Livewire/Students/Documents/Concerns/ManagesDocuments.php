<?php

namespace App\Livewire\Students\Documents\Concerns;

use App\Models\Document;
use App\Models\File;
use App\Models\FileStudentUpload;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

trait ManagesDocuments
{
    public string $searchDocuments = '';
    public string $statusFilter    = '';

    // ─── Asignar documentos pendientes ───────────────────────────────────────────

    public function assignPendingDocuments(): void
    {
        if (! $this->student) return;

        // Desactivar documentos de otros periodos
        Document::where('student_id', $this->student->id)
            ->whereHas('file', fn($q) => $q->where('period_id', '!=', $this->student->period_id))
            ->update(['is_active' => false]);

        $files = File::where('period_id', $this->student->period_id)->get();

        foreach ($files as $file) {
            // Individual + no admin_only → verificar que tenga archivo asignado
            if ($file->is_individual && $file->upload_mode !== 'admin_only') {
                $exists = FileStudentUpload::where('file_id', $file->id)
                    ->where('student_id', $this->student->id)
                    ->exists();

                if (! $exists) continue;
            }

            $document       = Document::firstOrNew([
                'student_id' => $this->student->id,
                'file_id'    => $file->id,
            ]);
            $document->name      = $file->name;
            $document->is_active = true;

            if (! $document->exists) {
                $document->status = in_array($file->upload_mode, ['user_only', 'bidirectional'])
                    ? 'en_revision'
                    : 'revisado';
            }

            $document->save();
        }

        // Desactivar documentos cuyo file ya no pertenece al periodo
        Document::where('student_id', $this->student->id)
            ->where('is_active', true)
            ->whereDoesntHave('file', fn($q) => $q->where('period_id', $this->student->period_id))
            ->update(['is_active' => false]);
    }

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
                    $limitDate = $this->effectiveDate($doc);

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
        }

        return compact('informativeDocuments', 'submissionDocuments', 'stats');
    }

    private function effectiveDate(Document $doc): ?Carbon
    {
        $general = $doc->file?->limit_date ? Carbon::parse($doc->file->limit_date) : null;
        $custom  = $doc->custom_limit_date ? Carbon::parse($doc->custom_limit_date) : null;

        return ($general && $custom && $custom->greaterThan($general)) ? $custom : $general;
    }

    // ─── Helpers ─────────────────────────────────────────────────────────────────

    public function canUploadFile(Document $document): bool
    {
        return $document->file && in_array($document->file->upload_mode, ['user_only', 'bidirectional']);
    }

    public function shouldShowAdminFiles(Document $document): bool
    {
        return $document->file && in_array($document->file->upload_mode, ['admin_only', 'bidirectional']);
    }

    public function getFileToDisplay(Document $document): array
    {
        if (! $document->file) return [];

        $files      = [];
        $file       = $document->file;
        $uploadMode = $file->upload_mode;

        // user_only → archivos del admin (Word + PDF) para que el estudiante los descargue
        if ($uploadMode === 'user_only') {
            if ($file->file_path && Storage::disk('local')->exists($file->file_path)) {
                $files[] = ['path' => $file->file_path, 'name' => $file->name_file, 'type' => 'admin_word'];
            }
            if ($file->example_path && Storage::disk('local')->exists($file->example_path)) {
                $files[] = ['path' => $file->example_path, 'name' => $file->example_name_file, 'type' => 'admin_pdf'];
            }
            return $files;
        }

        // bidirectional → Word + PDF del admin
        if ($uploadMode === 'bidirectional') {
            if ($file->file_path && Storage::disk('local')->exists($file->file_path)) {
                $files[] = ['path' => $file->file_path, 'name' => $file->name_file, 'type' => 'admin_word'];
            }
            if ($file->example_path && Storage::disk('local')->exists($file->example_path)) {
                $files[] = ['path' => $file->example_path, 'name' => $file->example_name_file, 'type' => 'admin_pdf'];
            }
            return $files;
        }

        // admin_only → individual o general
        if ($uploadMode === 'admin_only') {
            if ($file->is_individual) {
                $upload = FileStudentUpload::where('file_id', $file->id)->where('student_id', $this->student->id)->first();
                if ($upload && Storage::disk('local')->exists($upload->file_path)) {
                    $files[] = ['path' => $upload->file_path, 'name' => $upload->name_file, 'type' => 'individual'];
                }
            } else {
                if ($file->file_path && Storage::disk('local')->exists($file->file_path)) {
                    $files[] = ['path' => $file->file_path, 'name' => $file->name_file, 'type' => 'admin_word'];
                }
                if ($file->example_path && Storage::disk('local')->exists($file->example_path)) {
                    $files[] = ['path' => $file->example_path, 'name' => $file->example_name_file, 'type' => 'admin_pdf'];
                }
            }
        }

        return $files;
    }

    public function formatSize(int $bytes): string
    {
        if ($bytes >= 1024 * 1024 * 1024) return round($bytes / (1024 * 1024 * 1024), 2) . ' GB';
        if ($bytes >= 1024 * 1024)        return round($bytes / (1024 * 1024), 2) . ' MB';
        if ($bytes >= 1024)               return round($bytes / 1024, 2) . ' KB';
        return $bytes . ' B';
    }

    public function effectiveDatePublic(Document $doc): ?Carbon
    {
        return $this->effectiveDate($doc);
    }
}
