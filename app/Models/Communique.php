<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
        'actualite_id',
        'evenement_id',
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

    public function actualite(): BelongsTo
    {
        return $this->belongsTo(Actualite::class, 'actualite_id');
    }

    public function evenement(): BelongsTo
    {
        return $this->belongsTo(Evenement::class, 'evenement_id');
    }
}
