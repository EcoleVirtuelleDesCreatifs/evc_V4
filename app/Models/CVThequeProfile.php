<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Model Eloquent pour les profils CVThèque
 */
class CVThequeProfile extends Model
{
    protected $table = 'cvtheque_profiles';

    protected $fillable = [
        'user_id',
        'professional_title',
        'professional_summary',
        'years_experience',
        'current_position',
        'current_company',
        'software_skills',
        'technical_skills',
        'languages',
        'professional_email',
        'professional_phone',
        'professional_website',
        'linkedin_profile',
        'behance_profile',
        'dribbble_profile',
        'instagram_profile',
        'job_type',
        'salary_expectation',
        'availability_date',
        'remote_work',
        'willing_to_relocate',
        'preferred_locations',
        'certifications',
        'formations_completed',
        'profile_visible',
        'profile_public',
        'allow_contact',
        'profile_completion_score',
        'last_updated_by_user',
        // Champs pour les fichiers joints
        'cv_file_path',
        'cv_file_name',
        'motivation_letter_path',
        'motivation_letter_name',
        'portfolio_files',
        'pressbook_file_path',
        'pressbook_file_name',
        'report_file_path',
        'report_file_name'
    ];

    protected $casts = [
        'software_skills' => 'array',
        'technical_skills' => 'array',
        'languages' => 'array',
        'preferred_locations' => 'array',
        'certifications' => 'array',
        'formations_completed' => 'array',
        'portfolio_files' => 'array', // Pour stocker plusieurs fichiers de réalisations
        'years_experience' => 'integer',
        'remote_work' => 'boolean',
        'willing_to_relocate' => 'boolean',
        'profile_visible' => 'boolean',
        'profile_public' => 'boolean',
        'allow_contact' => 'boolean',
        'profile_completion_score' => 'integer',
        'availability_date' => 'date',
        'last_updated_by_user' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    protected $attributes = [
        'years_experience' => 0,
        'job_type' => 'Tout',
        'remote_work' => false,
        'willing_to_relocate' => false,
        'profile_visible' => true,
        'profile_public' => false,
        'allow_contact' => true,
        'profile_completion_score' => 0
    ];

    /**
     * Relation avec le modèle User
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope pour les profils visibles
     */
    public function scopeVisible($query)
    {
        return $query->where('profile_visible', true);
    }

    /**
     * Scope pour les profils publics
     */
    public function scopePublic($query)
    {
        return $query->where('profile_public', true);
    }

    /**
     * Scope pour les profils qui acceptent les contacts
     */
    public function scopeContactable($query)
    {
        return $query->where('allow_contact', true);
    }

    /**
     * Accessor pour obtenir le nom complet du type de poste
     */
    public function getJobTypeFullNameAttribute(): string
    {
        $jobTypes = [
            'CDI' => 'Contrat à Durée Indéterminée',
            'CDD' => 'Contrat à Durée Déterminée',
            'Freelance' => 'Travailleur Indépendant',
            'Stage' => 'Stage',
            'Alternance' => 'Alternance',
            'Tout' => 'Tous types de contrats'
        ];

        return $jobTypes[$this->job_type] ?? $this->job_type;
    }

    /**
     * Accessor pour obtenir le niveau d'expérience en texte
     */
    public function getExperienceLevelAttribute(): string
    {
        if ($this->years_experience == 0) {
            return 'Débutant';
        } elseif ($this->years_experience <= 2) {
            return 'Junior';
        } elseif ($this->years_experience <= 5) {
            return 'Confirmé';
        } else {
            return 'Senior';
        }
    }

    /**
     * Accessor pour obtenir le pourcentage de complétion formaté
     */
    public function getCompletionPercentageAttribute(): string
    {
        return $this->profile_completion_score . '%';
    }

    /**
     * Mutator pour mettre à jour automatiquement last_updated_by_user
     */
    public function setUpdatedAt($value)
    {
        parent::setUpdatedAt($value);
        $this->attributes['last_updated_by_user'] = now();
    }

    /**
     * Vérifier si le profil est complet (score >= 80%)
     */
    public function isComplete(): bool
    {
        return $this->profile_completion_score >= 80;
    }

    /**
     * Obtenir les logiciels maîtrisés sous forme de chaîne
     */
    public function getSoftwareSkillsStringAttribute(): string
    {
        if (empty($this->software_skills)) {
            return 'Aucun logiciel renseigné';
        }

        $softwareLabels = [
            'photoshop' => 'Photoshop',
            'illustrator' => 'Illustrator',
            'indesign' => 'InDesign',
            'figma' => 'Figma',
            'canva' => 'Canva',
            'after_effects' => 'After Effects'
        ];

        $skills = array_map(function($skill) use ($softwareLabels) {
            return $softwareLabels[$skill] ?? ucfirst($skill);
        }, $this->software_skills);

        return implode(', ', $skills);
    }

    /**
     * Obtenir les langues sous forme de chaîne
     */
    public function getLanguagesStringAttribute(): string
    {
        if (empty($this->languages)) {
            return 'Aucune langue renseignée';
        }

        return implode(', ', $this->languages);
    }

    /**
     * Obtenir les lieux préférés sous forme de chaîne
     */
    public function getPreferredLocationsStringAttribute(): string
    {
        if (empty($this->preferred_locations)) {
            return 'Aucune préférence géographique';
        }

        return implode(', ', $this->preferred_locations);
    }

    /**
     * Vérifier si le profil peut être contacté
     */
    public function canBeContacted(): bool
    {
        return $this->profile_visible && $this->allow_contact;
    }

    /**
     * Obtenir l'URL du profil LinkedIn formatée
     */
    public function getLinkedinUrlAttribute(): ?string
    {
        if (empty($this->linkedin_profile)) {
            return null;
        }

        // S'assurer que l'URL commence par https://
        if (!str_starts_with($this->linkedin_profile, 'http')) {
            return 'https://' . $this->linkedin_profile;
        }

        return $this->linkedin_profile;
    }

    /**
     * Obtenir l'URL du site web professionnel formatée
     */
    public function getWebsiteUrlAttribute(): ?string
    {
        if (empty($this->professional_website)) {
            return null;
        }

        // S'assurer que l'URL commence par https://
        if (!str_starts_with($this->professional_website, 'http')) {
            return 'https://' . $this->professional_website;
        }

        return $this->professional_website;
    }
}
