<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GirlPrivate extends Model
{
    protected $fillable = ['user_id', 'description', 'video', 'photos'];

    protected $casts = [
        'photos' => 'array'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

