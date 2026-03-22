<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Semester extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'name',
        'is_active',
    ];

    // 🔗 Semestre ↔ Periodos (muchos a muchos)
    public function periods()
    {
        return $this->belongsToMany(Period::class);
    }

    // 👨‍🎓 Estudiantes en este semestre
    public function students()
    {
        return $this->hasMany(Student::class);
    }
}
