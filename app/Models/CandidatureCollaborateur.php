<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CandidatureCollaborateur extends Model
{
    protected $table = 'candidatures_collaborateurs';

    protected $fillable = [
        'prenom',
        'nom',
        'email',
        'telephone',
        'poste',
        'experience',
        'message',
        'cv_path',
        'portfolio',
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
    public function getNomCompletAttribute()
    {
        return $this->prenom . ' ' . $this->nom;
    }

    public function getPosteFormatteAttribute()
    {
        return ucfirst(str_replace('_', ' ', $this->poste));
    }
}
