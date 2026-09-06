<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CertificationEligibility extends Model
{
    protected $table = 'certification_eligibilities';

    protected $fillable = [
        'student_id',
        'formation',
        'system_status',
        'admin_status',
        'studio_creative_status',
        'studio_creative_name',
        'studio_creative_comment',
        'studio_creative_validated_by',
        'studio_creative_validated_at',
        'validated_by',
        'validated_at',
        'admin_comment',
        'last_evaluated_at',
    ];

    protected $casts = [
        'studio_creative_validated_at' => 'datetime',
        'validated_at' => 'datetime',
        'last_evaluated_at' => 'datetime',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    public function histories(): HasMany
    {
        return $this->hasMany(CertificationEligibilityHistory::class, 'certification_eligibility_id');
    }

    public function scopeForFormation($query, string $formation)
    {
        return $query->where('formation', $formation);
    }
}
