<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCandidatureRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            // Informations personnelles
            'nom_complet' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'age' => 'required|integer|min:10|max:100',
            'date_naissance' => 'required|date',
            'sexe' => 'required|in:M,F',
            'nationalite' => 'required|string|max:120',
            'photo_profil' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
            // Coordonnées
            'email' => 'required|string|email|max:255|unique:pre_registrations|unique:users,email',
            'whatsapp' => 'required|string|max:30',
            'ville_pays' => 'required|string|max:180',
            // Académiques & pro
            'niveau_etude' => 'required|string|max:255',
            'domaine_etude' => 'required|string|max:255',
            'competences' => 'required|string|max:1500',
            // Formation
            'programme' => 'required|string|in:design-graphique,community-manager,design-graphique-community-manager,intelligence-artificielle,gestion-informatique',
            'niveau_formation' => 'required|string|in:Aucune notion,Certaines notions,Monter en compétence',
            'motivation' => 'required|string|max:5000',
            'origine' => 'required|string|in:Réseaux sociaux,Ami,Publicité,Autre',
            // Équipements
            'ordinateur' => 'required|string|in:Oui,Non',
            'smartphone' => 'required|string|in:Oui,Non',
            'disponibilite' => 'required|in:semaine_soir,weekend,flexible',
            // Consentements
            'veracite' => 'accepted',
            'consentement' => 'accepted',
        ];
    }

    public function messages(): array
    {
        return [
            'required' => 'Le champ :attribute est obligatoire.',
            'email' => "L'adresse e-mail est invalide.",
            'integer' => 'Le champ :attribute doit être un nombre entier.',
            'min' => 'Le champ :attribute doit être au moins :min.',
            'max' => 'Le champ :attribute ne peut pas dépasser :max.',
            'date' => 'La date fournie est invalide.',
            'in' => 'La valeur sélectionnée pour :attribute est invalide.',
            'image' => 'Le champ :attribute doit être une image.',
            'mimes' => 'Le champ :attribute doit être de type :values.',
            'accepted' => 'Vous devez accepter :attribute.',
            'unique' => 'Cette :attribute est déjà utilisée.',
        ];
    }

    public function attributes(): array
    {
        return [
            'nom_complet' => 'nom complet',
            'prenom' => 'prénom',
            'age' => 'âge',
            'date_naissance' => 'date de naissance',
            'sexe' => 'sexe',
            'nationalite' => 'nationalité',
            'photo_profil' => 'photo de profil',
            'email' => 'adresse e-mail',
            'whatsapp' => 'numéro WhatsApp',
            'ville_pays' => 'ville / pays de résidence',
            'niveau_etude' => 'niveau d’étude',
            'domaine_etude' => 'domaine d’étude',
            'competences' => 'compétences',
            'programme' => 'programme souhaité',
            'niveau_formation' => 'niveau pour la formation',
            'motivation' => 'motivation',
            'origine' => "comment vous avez connu l’EVC",
            'ordinateur' => 'ordinateur',
            'smartphone' => 'smartphone',
            'disponibilite' => 'disponibilités',
            'veracite' => 'certification de véracité',
            'consentement' => 'consentement',
        ];
    }
}
