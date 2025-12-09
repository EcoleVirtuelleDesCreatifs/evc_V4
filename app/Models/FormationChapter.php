<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Formation;

class FormationChapter extends Model
{
    use HasFactory;

    protected $fillable = [
        'formation_id',
        'title',
        'description',
        'order',
        'duration',
        'video_url',
    ];

    protected $casts = [
        'order' => 'integer',
        'duration' => 'integer',
    ];

    /**
     * Relation avec la formation
     */
    public function formation()
    {
        return $this->belongsTo(Formation::class);
    }
}
