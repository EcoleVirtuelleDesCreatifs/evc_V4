<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'category',
        'description',
        'link',
        'tags',
        'software_used',
        'deadline',
        'thumbnail_image',
        'status'
    ];

    protected $casts = [
        'software_used' => 'array', // Cast JSON en array automatiquement
        'deadline' => 'date',
    ];

    /**
     * Relation avec l'utilisateur (étudiant)
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relation avec les images du projet
     */
    public function images(): HasMany
    {
        return $this->hasMany(ProjectImage::class)->orderBy('order_index');
    }

    /**
     * Obtenir l'image principale (thumbnail)
     */
    public function thumbnailImage()
    {
        return $this->images()->where('is_thumbnail', true)->first();
    }

    /**
     * Obtenir toutes les images sauf la thumbnail
     */
    public function otherImages()
    {
        return $this->images()->where('is_thumbnail', false);
    }

    /**
     * Compter le nombre d'images
     */
    public function getImagesCountAttribute()
    {
        return $this->images()->count();
    }

    /**
     * Obtenir la taille totale des fichiers en MB
     */
    public function getTotalSizeMbAttribute()
    {
        $totalBytes = $this->images()->sum('file_size');
        return round($totalBytes / (1024 * 1024), 2);
    }

    /**
     * Obtenir les tags sous forme d'array
     */
    public function getTagsArrayAttribute()
    {
        if (empty($this->tags)) {
            return [];
        }
        return array_map('trim', explode(',', $this->tags));
    }

    /**
     * Scopes pour les requêtes fréquentes
     */
    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }
}
