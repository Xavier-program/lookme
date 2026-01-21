<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Code extends Model
{
    protected $fillable = [
        'user_id',
        'code',
        'expires_at',
    ];

    public function girl()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
