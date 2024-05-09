<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TOTPSecret extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'secret'
    ];

    function user()
    {
        return $this->belongsTo(User::class);
    }
}
