<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Storage;

class Document extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'file_id',           // relación con File
        'name',              // nombre del documento/entrega
        'student_file_path', // archivo subido por el estudiante
        'student_file_name', // nombre original del archivo
        'status',            // en_revision, revisado, rechazado
        'comments',
        'is_active',         // nuevo: indica si el documento está activo para el periodo actual
        'custom_limit_date',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'custom_limit_date' => 'date', // para manejarlo como Carbon
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function file()
    {
        return $this->belongsTo(File::class);
    }

    /**
     * El estudiante puede subir su propio archivo para este documento
     * (según el upload_mode configurado por el admin en File).
     */
    public function canUploadFile(): bool
    {
        return $this->file && in_array($this->file->upload_mode, ['user_only', 'bidirectional']);
    }

    /**
     * Fecha límite real: la personalizada (custom_limit_date) manda sobre la
     * general del File solo si es más tardía — nunca para acortar el plazo.
     */
    public function effectiveLimitDate(): ?Carbon
    {
        $general = $this->file?->limit_date ? Carbon::parse($this->file->limit_date) : null;
        $custom  = $this->custom_limit_date;

        return ($general && $custom && $custom->greaterThan($general)) ? $custom : $general;
    }

    /**
     * Archivos que el estudiante puede ver/descargar: los del admin
     * (según upload_mode) más el propio si ya lo subió.
     */
    public function filesToDisplay(): array
    {
        if (! $this->file) return [];

        $files      = [];
        $file       = $this->file;
        $uploadMode = $file->upload_mode;

        // user_only / bidirectional → archivos del admin (Word + PDF) para descarga
        if ($uploadMode === 'user_only' || $uploadMode === 'bidirectional') {
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
                $upload = FileStudentUpload::where('file_id', $file->id)->where('student_id', $this->student_id)->first();
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
}
