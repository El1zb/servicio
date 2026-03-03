<?php

namespace App\Livewire\Students\Documents\Concerns;

use App\Models\Document;
use App\Models\File;
use App\Models\FileStudentUpload;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

trait ManagesDocuments
{
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
        $documents         = collect();
        $adminOnlyDocuments = collect();

        if ($this->student && $this->student->status === 'aprobado') {
            $all = Document::where('student_id', $this->student->id)
                ->with(['file', 'file.studentUploads' => fn($q) => $q->where('student_id', $this->student->id)])
                ->where('is_active', true)
                ->get();

            $adminOnlyDocuments = $all->filter(fn($d) => $d->file?->upload_mode === 'admin_only');

            $documents = $all
                ->filter(fn($d) => $d->file?->upload_mode !== 'admin_only')
                ->sortByDesc(fn($d) => $this->effectiveDate($d) ?? now())
                ->groupBy(fn($d) => $this->effectiveDate($d)?->format('Y-m-d') ?? 'Sin fecha');
        }

        return compact('documents', 'adminOnlyDocuments');
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

        // user_only → solo el archivo que subió el estudiante
        if ($uploadMode === 'user_only') {
            if ($document->student_file_path && Storage::disk('public')->exists($document->student_file_path)) {
                $files[] = ['path' => $document->student_file_path, 'name' => $document->student_file_name, 'type' => 'student_upload'];
            }
            return $files;
        }

        // bidirectional → Word + PDF del admin
        if ($uploadMode === 'bidirectional') {
            if ($file->file_path && Storage::disk('public')->exists($file->file_path)) {
                $files[] = ['path' => $file->file_path, 'name' => $file->name_file, 'type' => 'admin_word'];
            }
            if ($file->example_path && Storage::disk('public')->exists($file->example_path)) {
                $files[] = ['path' => $file->example_path, 'name' => $file->example_name_file, 'type' => 'admin_pdf'];
            }
            return $files;
        }

        // admin_only → individual o general
        if ($uploadMode === 'admin_only') {
            if ($file->is_individual) {
                $upload = FileStudentUpload::where('file_id', $file->id)->where('student_id', $this->student->id)->first();
                if ($upload && Storage::disk('public')->exists($upload->file_path)) {
                    $files[] = ['path' => $upload->file_path, 'name' => $upload->name_file, 'type' => 'individual'];
                }
            } else {
                if ($file->file_path && Storage::disk('public')->exists($file->file_path)) {
                    $files[] = ['path' => $file->file_path, 'name' => $file->name_file, 'type' => 'admin_word'];
                }
                if ($file->example_path && Storage::disk('public')->exists($file->example_path)) {
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
}