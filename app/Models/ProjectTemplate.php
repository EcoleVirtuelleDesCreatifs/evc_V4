<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectTemplate extends Model
{
    protected $fillable = [
        'title',
        'category',
        'description',
        'link',
        'tags',
        'software_used',
        'thumbnail_image',
        'brief_content',
        'default_deadline_days',
        'is_active',
    ];

    protected $casts = [
        'software_used' => 'array',
        'is_active' => 'boolean',
    ];
}
