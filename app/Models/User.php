<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function coordinateur()
    {
        return $this->hasOne(Coordinateur::class);
    }

    public function collectesValidees()
    {
        return $this->hasMany(Collecte::class, 'validated_by');
    }

    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isCoordinateur()
    {
        return $this->role === 'coordinateur';
    }
}
