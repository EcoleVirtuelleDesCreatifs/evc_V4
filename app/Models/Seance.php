<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Seance extends Model
{
    use HasFactory;

    protected $table = 'seances';

    protected $fillable = [
        'title',
        'module',
        'formateur',
        'description',
        'formation',
        'type',
        'location',
        'meet_link',
        'scheduled_at',
        'duration_minutes',
        'status',
        'created_by',
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
        'duration_minutes' => 'integer',
    ];

    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class, 'seance_id');
    }

    public function qrToken(): HasOne
    {
        return $this->hasOne(SeanceQrToken::class, 'seance_id')->latestOfMany('created_at');
    }

    public function scopeForFormation($query, string $formation)
    {
        return $query->where('formation', $formation);
    }

    public function scopeVisible($query)
    {
        return $query->where('status', '!=', 'cancelled');
    }

    public function getEndsAtAttribute()
    {
        return $this->scheduled_at
            ? $this->scheduled_at->copy()->addMinutes($this->duration_minutes)
            : null;
    }

    public function isOngoing(): bool
    {
        if ($this->status === 'cancelled' || !$this->scheduled_at || !$this->ends_at) {
            return false;
        }

        $now = now();
        return $this->scheduled_at <= $now && $now < $this->ends_at;
    }
}
