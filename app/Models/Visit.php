<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Visit extends Model
{
    protected $fillable = [
        'session_id',
        'ip',
        'path',
        'user_agent',
        'product_id',
        'visited_at',
    ];

    protected $casts = [
        'product_id' => 'integer',
        'visited_at' => 'datetime',
    ];
}
