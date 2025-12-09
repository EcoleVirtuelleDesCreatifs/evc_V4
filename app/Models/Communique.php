<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Communique extends Model
{
    use HasFactory;

    protected $fillable = [
        'content',
        'is_active',
        'view_count',
        'order',
        'start_at',
        'end_at',
        'target_audience',
    ];

    public const TARGETS = [
        'all' => 'Toutes les classes',
        'Design Graphique' => 'Design Graphique',
        'Community Management' => 'Community Management',
        'Gestion Informatique' => 'Gestion Informatique',
        'Intelligence Artificielle' => 'Intelligence Artificielle',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'start_at' => 'datetime',
        'end_at' => 'datetime',
    ];

    /**
     * Scope a query to only include active communiques within date range.
     */
    public function scopeActive($query)
    {
        $now = now();
        return $query->where('is_active', true)
                     ->where(function ($q) use ($now) {
                         $q->whereNull('start_at')
                           ->orWhere('start_at', '<=', $now);
                     })
                     ->where(function ($q) use ($now) {
                         $q->whereNull('end_at')
                           ->orWhere('end_at', '>=', $now);
                     });
    }
}
