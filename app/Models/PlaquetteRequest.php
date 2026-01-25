<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlaquetteRequest extends Model
{
    protected $table = 'plaquette_requests';

    protected $fillable = [
        'plaquette_id',
        'nom',
        'prenoms',
        'type_formation',
        'pays',
        'ville',
        'whatsapp',
        'email',
        'niveau_etude',
        'motivation',
        'status',
        'approved_by_admin_id',
        'approved_at',
        'rejected_at',
        'admin_comment',
    ];

    protected $casts = [
        'approved_at' => 'datetime',
        'rejected_at' => 'datetime',
    ];

    public function plaquette()
    {
        return $this->belongsTo(Plaquette::class);
    }
}
