<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class File extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'name',
        'limit_date',
        'firman',
        'observations',
        'period_id',
        'student_id',
        'file_path',
        'name_file',
        'example_path',
        'example_name_file',
        'max_size',
        'upload_mode',
        'is_individual'
    ];

    public function period()
    {
        return $this->belongsTo(Period::class);
    }
    

    public function documents()
    {
        return $this->hasMany(Document::class);
    }

    public function studentUploads()
    {
        return $this->hasMany(FileStudentUpload::class);
    }

}
