<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SaopEligibilityTest extends Model
{
    protected $fillable = [
        'full_name',
        'email',
        'whatsapp',
        'formation',
        'answers',
        'duration_seconds',
        'started_at',
        'submitted_at',
        'status',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'answers' => 'array',
        'started_at' => 'datetime',
        'submitted_at' => 'datetime',
    ];
}
