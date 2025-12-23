<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class Actualite extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'excerpt',
        'content',
        'cover_image',
        'cover_image_alt',
        'category',
        'visibility',
        'formations',
        'status',
        'is_featured',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'admin_id',
        'views_count',
        'published_at',
    ];

    protected $casts = [
        'formations' => 'array',
        'is_featured' => 'boolean',
        'published_at' => 'datetime',
    ];

    /**
     * Get the author (admin) of the actualite
     */
    public function author()
    {
        return $this->belongsTo(Admin::class, 'admin_id');
    }

    /**
     * Scope for published actualites
     */
    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    /**
     * Scope for featured actualites
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    /**
     * Scope for draft actualites
     */
    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }

    public function getCoverImageUrlAttribute()
    {
        if ($this->cover_image) {
            $coverImage = (string) $this->cover_image;

            if (Str::startsWith($coverImage, ['http://', 'https://'])) {
                return $coverImage;
            }

            $coverImage = ltrim($coverImage, '/');

            if (Str::startsWith($coverImage, 'storage/')) {
                $coverImage = Str::after($coverImage, 'storage/');
            }

            return Storage::disk('public')->url($coverImage);
        }

        return asset('assets/img/logo.png');
    }
}
