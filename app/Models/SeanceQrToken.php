<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SeanceQrToken extends Model
{
    use HasFactory;

    protected $fillable = [
        'seance_id',
        'token',
        'expires_at',
        'closed_at',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'closed_at' => 'datetime',
    ];

    public function seance(): BelongsTo
    {
        return $this->belongsTo(Seance::class);
    }

    public function isValid(): bool
    {
        return $this->closed_at === null && $this->expires_at !== null && $this->expires_at->isFuture();
    }

    public function isOpen(): bool
    {
        return $this->closed_at === null;
    }
}
