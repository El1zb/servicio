<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use NotificationChannels\WebPush\HasPushSubscriptions;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles, HasPushSubscriptions;

    /** @var list<string> */
    protected $fillable = [
        'name',
        'email',
        'password',
        'created_by',
    ];

    /** @var list<string> */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /** @return array<string, string> */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function initials(): string
    {
        return Str::of($this->name)
            ->explode(' ')
            ->take(2)
            ->map(fn ($word) => Str::substr($word, 0, 1))
            ->implode('');
    }

    /**
     * Primeros dos nombres/apellidos del usuario, para mostrar en la UI sin
     * ocupar todo el nombre completo (ej. "Marian Elizabeth" en vez de
     * "Marian Elizabeth Cardenas Andrade").
     */
    public function shortName(): string
    {
        return Str::of($this->name)
            ->explode(' ')
            ->take(2)
            ->implode(' ');
    }





    

public function student()
{
    return $this->hasOne(\App\Models\Student::class, 'user_id');
}

    /**
     * Admin que creó esta cuenta (solo para trazabilidad, sin uso en UI).
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}



