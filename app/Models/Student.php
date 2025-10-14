<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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
    ];

    // Relaciones
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
}