<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Document extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'name',
        'file_path',
        'status',
        'comments',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
