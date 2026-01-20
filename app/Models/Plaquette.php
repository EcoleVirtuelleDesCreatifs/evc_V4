<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Plaquette extends Model
{
    protected $table = 'plaquettes';

    protected $fillable = [
        'title',
        'description',
        'formation_id',
        'file_path',
        'original_filename',
        'file_size',
        'start_date',
        'end_date',
        'format',
        'download_count',
        'is_published',
        'published_at',
        'is_active',
    ];

    protected $casts = [
        'file_size' => 'integer',
        'download_count' => 'integer',
        'start_date' => 'date',
        'end_date' => 'date',
        'is_published' => 'boolean',
        'published_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    public function formation()
    {
        return $this->belongsTo(Formation::class);
    }
}
