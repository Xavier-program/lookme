<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CodeUsage extends Model
{
    protected $table = 'code_usages';

    protected $fillable = [
        'code_id',
        'girl_id',
        'ip',
        'user_agent',
        'used_at'
    ];

    public function girl()
    {
        return $this->belongsTo(User::class, 'girl_id');
    }

    public function code()
    {
        return $this->belongsTo(Code::class, 'code_id');
    }
}
