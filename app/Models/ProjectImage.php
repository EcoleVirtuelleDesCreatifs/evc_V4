<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProjectImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'filename',
        'original_name',
        'mime_type',
        'file_size',
        'file_path',
        'is_thumbnail',
        'order_index'
    ];

    protected $casts = [
        'is_thumbnail' => 'boolean',
        'file_size' => 'integer',
        'order_index' => 'integer'
    ];

    /**
     * Relation avec le projet
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * Obtenir la taille du fichier formatée
     */
    public function getFormattedSizeAttribute()
    {
        $bytes = $this->file_size;
        $units = ['B', 'KB', 'MB', 'GB'];
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, 2) . ' ' . $units[$i];
    }

    /**
     * Obtenir l'URL complète du fichier
     */
    public function getUrlAttribute()
    {
        return asset('storage/' . $this->file_path);
    }

    /**
     * Vérifier si c'est une image
     */
    public function getIsImageAttribute()
    {
        return in_array($this->mime_type, [
            'image/jpeg',
            'image/jpg',
            'image/png',
            'image/gif',
            'image/webp',
            'image/svg+xml'
        ]);
    }

    /**
     * Scopes
     */
    public function scopeThumbnails($query)
    {
        return $query->where('is_thumbnail', true);
    }

    public function scopeNonThumbnails($query)
    {
        return $query->where('is_thumbnail', false);
    }

    public function scopeOrderedByIndex($query)
    {
        return $query->orderBy('order_index');
    }
}
