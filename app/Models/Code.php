<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Code extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'expires_at',
        'batch_id',
        'used_at',
        'girl_id',
        'ip',
        'user_agent'
    ];

    // 👇 Aquí le dices a Laravel que expires_at es una fecha
    protected $casts = [
        'expires_at' => 'datetime',
        'used_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];



    public function girl()
{
    return $this->belongsTo(\App\Models\User::class, 'girl_id');
}

}
