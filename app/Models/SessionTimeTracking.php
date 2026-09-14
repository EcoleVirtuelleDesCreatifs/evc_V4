<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SessionTimeTracking extends Model
{
    protected $table = 'session_time_tracking';

    protected $fillable = [
        'student_id',
        'seance_id',
        'user_id',
        'page_type',
        'session_start',
        'session_end',
        'duration_seconds',
        'is_active',
        'user_agent',
        'ip_address',
        'metadata',
    ];

    protected $casts = [
        'session_start' => 'datetime',
        'session_end' => 'datetime',
        'duration_seconds' => 'integer',
        'is_active' => 'boolean',
        'metadata' => 'array',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function seance(): BelongsTo
    {
        return $this->belongsTo(Seance::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeForStudent($query, $studentId)
    {
        return $query->where('student_id', $studentId);
    }

    public function scopeForSeance($query, $seanceId)
    {
        return $query->where('seance_id', $seanceId);
    }

    public function scopeForPageType($query, $pageType)
    {
        return $query->where('page_type', $pageType);
    }

    public function scopeToday($query)
    {
        return $query->whereDate('session_start', today());
    }

    public function getTotalDurationAttribute(): int
    {
        if ($this->is_active && $this->session_start) {
            return $this->duration_seconds + now()->diffInSeconds($this->session_start);
        }
        return $this->duration_seconds;
    }

    public function endSession(): void
    {
        if ($this->is_active && $this->session_start) {
            $this->session_end = now();
            $this->duration_seconds = $this->session_start->diffInSeconds($this->session_end);
            $this->is_active = false;
            $this->save();
        }
    }
}
