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
        'photo',
        'email',
        'whatsapp',
        'pays',
        'niveau_etude',
        'choix_formation',
        'has_computer',
        'has_smartphone',
        'disponibilite',
        'motivation',
        'status',
    ];
}
