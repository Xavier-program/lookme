<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TermsAcceptance extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'role',
        'accepted_at',
        'ip_address',
        'user_agent',
        'terms_version',
        'accepted_from',
    ];

    public $timestamps = true;

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
