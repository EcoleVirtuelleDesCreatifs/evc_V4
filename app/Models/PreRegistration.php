<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PreRegistration extends Model
{
    use \Illuminate\Database\Eloquent\Factories\HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nom',
        'prenom',
        'age',
        'date_naissance',
        'sexe',
        'nationalite',
        'photo',
        'email',
        'whatsapp',
        'ville',
        'pays',
        'niveau_etude',
        'domaine_etude',
        'competences',
        'choix_formation',
        'niveau_dans_formation',
        'programme',
        'how_known',
        'has_computer',
        'has_smartphone',
        'disponibilite',
        'motivation',
        'certify',
        'consent',
        'commercial_admin_id',
        'status',
    ];
}
