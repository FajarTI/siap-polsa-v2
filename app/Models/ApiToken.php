<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApiToken extends Model
{
    protected $fillable = [
        'provider',
        'token',
        'expired_at',
        'refreshed_at',
    ];

    protected $casts = [
        'expired_at' => 'datetime',
        'refreshed_at' => 'datetime',
    ];
}
