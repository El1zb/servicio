<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class File extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'limit_date',
        'period_id',
        'file_path',
        'name_file',
        'example_path',
        'example_name_file',
        'max_size',
    ];

    public function period()
    {
        return $this->belongsTo(Period::class);
    }

    public function documents()
    {
        return $this->hasMany(Document::class);
    }
}
