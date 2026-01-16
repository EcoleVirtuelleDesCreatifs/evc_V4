<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityReport extends Model
{
    protected $table = 'activity_reports';

    protected $fillable = [
        'title',
        'year',
        'description',
        'file_path',
        'original_filename',
        'file_size',
        'download_count',
        'is_published',
        'published_at',
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'published_at' => 'datetime',
        'year' => 'integer',
        'file_size' => 'integer',
        'download_count' => 'integer',
    ];
}
