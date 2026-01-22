<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CodeBatch extends Model
{
    protected $fillable = ['quantity'];

    public function codes()
    {
        return $this->hasMany(Code::class, 'batch_id');
    }
}
