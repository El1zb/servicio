<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FileStudentUpload extends Model
{
    use HasFactory;

    protected $fillable = [
        'file_id',
        'student_id',
        'file_path',
        'name_file',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function file()
    {
        return $this->belongsTo(File::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
