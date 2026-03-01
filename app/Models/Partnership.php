<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Partnership extends Model
{
    protected $fillable = [
        'slug',
        'prefix',
        'name',
        'subtitle',
        'document_path',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}
