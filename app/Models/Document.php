<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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
        'custom_limit_date' => 'date', // 🔹 para manejarlo como Carbon
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    // Relación opcional al documento definido por el admin
    public function file()
    {
        return $this->belongsTo(File::class);
    }
}
