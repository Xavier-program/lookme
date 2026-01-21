<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

use App\Models\CodeUsage; // <-- IMPORTAR

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'name_artist',
        'photo_public',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // RELACIÓN A code_usages
    public function codeUsages()
    {
        return $this->hasMany(CodeUsage::class);
    }

    // RELACIÓN A private (si ya la tienes)
    public function private()
    {
        return $this->hasOne(GirlPrivate::class);
    }
}
