<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class WebtvSubscriber extends Model
{
    protected $fillable = [
        'email',
        'name',
        'is_active',
        'verification_token',
        'verified_at',
        'last_notified_at',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'verified_at' => 'datetime',
        'last_notified_at' => 'datetime',
    ];

    /**
     * Génère un token de vérification unique
     */
    public static function generateVerificationToken()
    {
        return Str::random(60);
    }

    /**
     * Vérifie si l'abonné est vérifié
     */
    public function isVerified()
    {
        return !is_null($this->verified_at);
    }

    /**
     * Marque l'abonné comme vérifié
     */
    public function markAsVerified()
    {
        $this->verified_at = now();
        $this->verification_token = null;
        $this->save();
    }

    /**
     * Scope pour les abonnés actifs
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope pour les abonnés vérifiés
     */
    public function scopeVerified($query)
    {
        return $query->whereNotNull('verified_at');
    }
}
