<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class DesignProject extends Model
{
    use HasFactory;

    protected $table = 'design_projects';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'title',
        'description',
        'project_type',
        'project_mode',
        'software_used',
        'reference_url',
        'status',
        'progress_percentage',
        'completed_at'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'software_used' => 'array',
        'progress_percentage' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    /**
     * Project type labels for display
     */
    const PROJECT_TYPE_LABELS = [
        'logo' => 'Logo',
        'web' => 'Web Design',
        'print' => 'Print',
        'packaging' => 'Packaging',
        'illustration' => 'Illustration',
        'motion' => 'Motion Design',
        'strategy' => 'Stratégie',
        'autre' => 'Autre'
    ];

    /**
     * Status labels for display
     */
    const STATUS_LABELS = [
        'draft' => 'Brouillon',
        'active' => 'En cours',
        'validated' => 'Validé',
        'cancelled' => 'Annulé'
    ];

    /**
     * Mode labels for display
     */
    const MODE_LABELS = [
        'solo' => 'Solo',
        'groupe' => 'Groupe'
    ];

    /**
     * Get the user that owns the design project.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the files for the design project.
     */
    public function files()
    {
        return $this->hasMany(DesignProjectFile::class);
    }

    /**
     * Get preview files only.
     */
    public function previewFiles()
    {
        return $this->hasMany(DesignProjectFile::class)
            ->where('file_category', 'preview')
            ->where('mime_type', 'like', 'image/%');
    }

    /**
     * Get the first preview image.
     */
    public function firstPreviewImage()
    {
        return $this->hasOne(DesignProjectFile::class)
            ->where('file_category', 'preview')
            ->where('mime_type', 'like', 'image/%')
            ->orderBy('created_at', 'desc');
    }

    /**
     * Get project type label.
     */
    public function getProjectTypeLabelAttribute()
    {
        return self::PROJECT_TYPE_LABELS[$this->project_type] ?? $this->project_type;
    }

    /**
     * Get status label.
     */
    public function getStatusLabelAttribute()
    {
        return self::STATUS_LABELS[$this->status] ?? $this->status;
    }

    /**
     * Get mode label.
     */
    public function getModeLabelAttribute()
    {
        return self::MODE_LABELS[$this->project_mode] ?? $this->project_mode;
    }

    /**
     * Get formatted software list.
     */
    public function getFormattedSoftwareAttribute()
    {
        if (empty($this->software_used)) {
            return 'Non spécifié';
        }

        if (is_array($this->software_used)) {
            return implode(', ', $this->software_used);
        }

        return $this->software_used;
    }

    /**
     * Get formatted creation date.
     */
    public function getFormattedDateAttribute()
    {
        return $this->created_at->format('d/m/Y H:i');
    }

    /**
     * Get progress status.
     */
    public function getProgressStatusAttribute()
    {
        if ($this->progress_percentage >= 100) {
            return 'Terminé';
        } elseif ($this->progress_percentage >= 75) {
            return 'Presque terminé';
        } elseif ($this->progress_percentage >= 50) {
            return 'En bonne voie';
        } elseif ($this->progress_percentage >= 25) {
            return 'En cours';
        } else {
            return 'Démarré';
        }
    }

    /**
     * Scope for active projects.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope for validated projects.
     */
    public function scopeValidated($query)
    {
        return $query->where('status', 'validated');
    }

    /**
     * Scope for user projects.
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }
}
