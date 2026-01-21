<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GirlCodesHistory extends Model
{
    protected $fillable = ['user_id', 'code', 'used_at'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
