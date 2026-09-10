<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Cache;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'campus_id',
        'period_id',
        'career_id',
        'curp',
        'rfc',
        'control_number',
        'last_name_paterno',
        'last_name_materno',
        'name',
        'institutional_email',
        'personal_email',
        'phone',
        'reticular_progress', // decimal
        'semester_id',
        'system',
        'status',
        'rejection_reason',
    ];

    // Relaciones
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function campus()
    {
        return $this->belongsTo(Campus::class);
    }

    public function career()
    {
        return $this->belongsTo(Career::class);
    }

    public function period()
    {
        return $this->belongsTo(Period::class);
    }

    public function semester()
    {
        return $this->belongsTo(Semester::class);
    }

    public function documents()
    {
        return $this->hasMany(Document::class);
    }

    public function uploadedFiles()
    {
        return $this->hasMany(FileStudentUpload::class);
    }

    /**
     * Crea/reactiva los documentos del periodo actual del estudiante y
     * desactiva los que ya no apliquen. Corre al entrar a su panel.
     *
     * Se throttlea con caché: recorre TODOS los files del periodo con
     * escrituras por cada uno, y el panel se visita mucho más seguido de lo
     * que cambia la configuración de documentos de un periodo. La clave
     * incluye period_id, así que un cambio de periodo se sincroniza al
     * instante sin esperar el TTL.
     */
    public function syncPendingDocuments(): void
    {
        $cacheKey = "student-docs-synced:{$this->id}:{$this->period_id}";

        if (Cache::has($cacheKey)) {
            return;
        }

        Document::where('student_id', $this->id)
            ->whereHas('file', fn ($q) => $q->where('period_id', '!=', $this->period_id))
            ->update(['is_active' => false]);

        $files = File::where('period_id', $this->period_id)->get();

        foreach ($files as $file) {
            // Individual + no admin_only → solo si ya tiene archivo asignado
            if ($file->is_individual && $file->upload_mode !== 'admin_only') {
                $exists = FileStudentUpload::where('file_id', $file->id)
                    ->where('student_id', $this->id)
                    ->exists();

                if (! $exists) continue;
            }

            $document = Document::firstOrNew([
                'student_id' => $this->id,
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

        Document::where('student_id', $this->id)
            ->where('is_active', true)
            ->whereDoesntHave('file', fn ($q) => $q->where('period_id', $this->period_id))
            ->update(['is_active' => false]);

        Cache::put($cacheKey, true, now()->addMinutes(5));
    }
}