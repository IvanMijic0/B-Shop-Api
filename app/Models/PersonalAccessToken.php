<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PersonalAccessToken extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'token',
        'last_used_at',
        'expires_at'
    ];

    function user()
    {
        return $this->belongsTo(User::class);
    }
}
