<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Period extends Model
{    
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'name',
        'start_date',
        'end_date',
        'is_active', // <-- importante para soft delete
    ];


    // Periodo ↔ Semestres (muchos a muchos)
    public function semesters()
    {
        return $this->belongsToMany(Semester::class);
    }

    // Estudiantes del periodo
    public function students()
    {
        return $this->hasMany(Student::class);
    }

    // Documentos configurados para el periodo
    public function files()
    {
        return $this->hasMany(File::class);
    }

    // Documentos subidos por los estudiantes del periodo
    public function documents()
    {
        return $this->hasManyThrough(Document::class, Student::class);
    }
}
