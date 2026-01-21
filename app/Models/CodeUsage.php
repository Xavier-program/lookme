<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CodeUsage extends Model
{
    protected $table = 'code_usages';

    protected $fillable = [
        'code_id',
        'user_id',
        'ip',
        'user_agent',
        'used_at'
    ];

    public $timestamps = false;

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
