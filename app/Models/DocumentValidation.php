<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

/**
 * Model pour la gestion des validations de documents CVThèque
 */
class DocumentValidation extends Model
{
    protected $table = 'document_validations';

    protected $fillable = [
        'user_id',
        'document_type',
        'document_name',
        'document_path',
        'status',
        'admin_comment',
        'validated_by',
        'validated_at',
        'file_size',
        'mime_type'
    ];

    protected $casts = [
        'validated_at' => 'datetime',
        'file_size' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    // Constantes pour les statuts
    const STATUS_EN_COURS = 'en_cours';
    const STATUS_VALIDE = 'valide';
    const STATUS_REJETE = 'rejete';

    // Constantes pour les types de documents
    const TYPE_CV = 'cv';
    const TYPE_MOTIVATION = 'motivation';
    const TYPE_PRESSBOOK = 'pressbook';
    const TYPE_RAPPORT = 'rapport';
    const TYPE_REALISATION = 'realisation';

    /**
     * Relation avec l'utilisateur
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relation avec l'admin qui a validé
     */
    public function validator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'validated_by');
    }

    /**
     * Scope pour les documents en cours de validation
     */
    public function scopeEnCours($query)
    {
        return $query->where('status', self::STATUS_EN_COURS);
    }

    /**
     * Scope pour les documents validés
     */
    public function scopeValide($query)
    {
        return $query->where('status', self::STATUS_VALIDE);
    }

    /**
     * Scope pour les documents rejetés
     */
    public function scopeRejete($query)
    {
        return $query->where('status', self::STATUS_REJETE);
    }

    /**
     * Scope pour un type de document spécifique
     */
    public function scopeOfType($query, $type)
    {
        return $query->where('document_type', $type);
    }

    /**
     * Scope pour un utilisateur spécifique
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Obtenir le badge de statut avec couleur
     */
    public function getStatusBadgeAttribute(): array
    {
        switch ($this->status) {
            case self::STATUS_EN_COURS:
                return [
                    'text' => 'En cours d\'analyse',
                    'class' => 'bg-warning text-dark',
                    'icon' => 'fas fa-clock'
                ];
            case self::STATUS_VALIDE:
                return [
                    'text' => 'Validé',
                    'class' => 'bg-success',
                    'icon' => 'fas fa-check-circle'
                ];
            case self::STATUS_REJETE:
                return [
                    'text' => 'Rejeté',
                    'class' => 'bg-danger',
                    'icon' => 'fas fa-times-circle'
                ];
            default:
                return [
                    'text' => 'Inconnu',
                    'class' => 'bg-secondary',
                    'icon' => 'fas fa-question-circle'
                ];
        }
    }

    /**
     * Obtenir le nom du type de document formaté
     */
    public function getDocumentTypeNameAttribute(): string
    {
        $types = [
            self::TYPE_CV => 'CV',
            self::TYPE_MOTIVATION => 'Lettre de motivation',
            self::TYPE_PRESSBOOK => 'Pressbook',
            self::TYPE_RAPPORT => 'Rapport de fin de formation',
            self::TYPE_REALISATION => 'Réalisation'
        ];

        return $types[$this->document_type] ?? ucfirst($this->document_type);
    }

    /**
     * Obtenir la taille du fichier formatée
     */
    public function getFormattedFileSizeAttribute(): string
    {
        if (!$this->file_size) {
            return 'N/A';
        }

        $bytes = $this->file_size;
        $units = ['B', 'KB', 'MB', 'GB'];
        $factor = floor((strlen($bytes) - 1) / 3);
        
        return sprintf("%.2f %s", $bytes / pow(1024, $factor), $units[$factor]);
    }

    /**
     * Vérifier si le document peut être modifié
     */
    public function canBeModified(): bool
    {
        return $this->status === self::STATUS_EN_COURS || $this->status === self::STATUS_REJETE;
    }

    /**
     * Valider le document
     */
    public function validate($adminId, $comment = null): bool
    {
        return $this->update([
            'status' => self::STATUS_VALIDE,
            'validated_by' => $adminId,
            'validated_at' => now(),
            'admin_comment' => $comment
        ]);
    }

    /**
     * Rejeter le document
     */
    public function reject($adminId, $comment): bool
    {
        return $this->update([
            'status' => self::STATUS_REJETE,
            'validated_by' => $adminId,
            'validated_at' => now(),
            'admin_comment' => $comment
        ]);
    }

    /**
     * Remettre en cours de validation
     */
    public function resetToPending(): bool
    {
        return $this->update([
            'status' => self::STATUS_EN_COURS,
            'validated_by' => null,
            'validated_at' => null,
            'admin_comment' => null
        ]);
    }

    /**
     * Obtenir tous les types de documents disponibles
     */
    public static function getDocumentTypes(): array
    {
        return [
            self::TYPE_CV => 'CV',
            self::TYPE_MOTIVATION => 'Lettre de motivation',
            self::TYPE_PRESSBOOK => 'Pressbook',
            self::TYPE_RAPPORT => 'Rapport de fin de formation',
            self::TYPE_REALISATION => 'Réalisation'
        ];
    }

    /**
     * Obtenir tous les statuts disponibles
     */
    public static function getStatuses(): array
    {
        return [
            self::STATUS_EN_COURS => 'En cours',
            self::STATUS_VALIDE => 'Validé',
            self::STATUS_REJETE => 'Rejeté'
        ];
    }
}
