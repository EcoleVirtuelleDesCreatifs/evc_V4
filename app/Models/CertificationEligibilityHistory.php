<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CertificationEligibilityHistory extends Model
{
    protected $table = 'certification_eligibility_histories';

    protected $fillable = [
        'certification_eligibility_id',
        'from_system_status',
        'to_system_status',
        'from_admin_status',
        'to_admin_status',
        'from_studio_status',
        'to_studio_status',
        'admin_id',
        'comment',
    ];

    public function eligibility(): BelongsTo
    {
        return $this->belongsTo(CertificationEligibility::class, 'certification_eligibility_id');
    }

    public function admin(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'admin_id');
    }
}
