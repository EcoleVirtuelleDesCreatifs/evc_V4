<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DemandePartenariat extends Model
{
    protected $table = 'demandes_partenariat';

    protected $fillable = [
        'organisation',
        'nom_contact',
        'email',
        'telephone',
        'site_web',
        'type_partenariat',
        'secteur',
        'message',
        'statut',
        'notes_admin',
        'date_traitement',
    ];

    protected $casts = [
        'date_traitement' => 'datetime',
    ];

    // Scopes
    public function scopeNouveau($query)
    {
        return $query->where('statut', 'nouveau');
    }

    public function scopeEnCours($query)
    {
        return $query->where('statut', 'en_cours');
    }

    public function scopeAccepte($query)
    {
        return $query->where('statut', 'accepte');
    }

    public function scopeRefuse($query)
    {
        return $query->where('statut', 'refuse');
    }

    // Accesseurs
    public function getTypePartenaritFormatteAttribute()
    {
        return ucfirst(str_replace('_', ' ', $this->type_partenariat));
    }
}
