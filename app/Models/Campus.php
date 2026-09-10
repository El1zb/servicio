<?php

namespace App\Models;

use App\Models\Concerns\CachesCatalog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Campus extends Model
{
    use HasFactory;
    use SoftDeletes;
    use CachesCatalog;

    protected $fillable = ['name', 'is_active'];

    protected $casts = ['is_active' => 'boolean'];

    public function students()
    {
        return $this->hasMany(Student::class);
    }

    public function careers()
    {
        return $this->belongsToMany(Career::class, 'campus_career');
    }
}
