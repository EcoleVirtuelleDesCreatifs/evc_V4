<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class Evenement extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'evenements';

    protected $fillable = [
        'title',
        'slug',
        'excerpt',
        'content',
        'event_date',
        'event_end_date',
        'location',
        'event_type',
        'registration_link',
        'cover_image',
        'cover_image_alt',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'visibility',
        'formations',
        'status',
        'is_featured',
        'published_at',
        'author_id',
        'views_count',
    ];

    protected $casts = [
        'event_date' => 'date',
        'event_end_date' => 'date',
        'formations' => 'array',
        'is_featured' => 'boolean',
        'published_at' => 'datetime',
        'views_count' => 'integer',
    ];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        // Auto-generate slug from title if not provided
        static::creating(function ($evenement) {
            if (empty($evenement->slug)) {
                $evenement->slug = Str::slug($evenement->title);
            }
        });

        // Set published_at when status changes to published
        static::saving(function ($evenement) {
            if ($evenement->status === 'published' && !$evenement->published_at) {
                $evenement->published_at = now();
            }
        });
    }

    /**
     * Get the author of the event.
     */
    public function author()
    {
        return $this->belongsTo(Admin::class, 'author_id');
    }

    /**
     * Scope a query to only include published events.
     */
    public function scopePublished($query)
    {
        return $query->where('status', 'published')
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now());
    }

    /**
     * Scope a query to only include draft events.
     */
    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }

    /**
     * Scope a query to only include featured events.
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    /**
     * Scope a query to only include upcoming events.
     */
    public function scopeUpcoming($query)
    {
        return $query->where('event_date', '>=', now()->toDateString())
            ->orderBy('event_date', 'asc');
    }

    /**
     * Scope a query to only include past events.
     */
    public function scopePast($query)
    {
        return $query->where('event_date', '<', now()->toDateString())
            ->orderBy('event_date', 'desc');
    }

    /**
     * Check if the event is published.
     */
    public function isPublished(): bool
    {
        return $this->status === 'published' &&
            $this->published_at &&
            $this->published_at->isPast();
    }

    /**
     * Check if the event is upcoming.
     */
    public function isUpcoming(): bool
    {
        return $this->event_date >= now()->toDateString();
    }

    /**
     * Check if the event is past.
     */
    public function isPast(): bool
    {
        return $this->event_date < now()->toDateString();
    }

    /**
     * Increment views count.
     */
    public function incrementViews()
    {
        $this->increment('views_count');
    }

    /**
     * Get the event's cover image URL.
     */
    public function getCoverImageUrlAttribute()
    {
        if ($this->cover_image) {
            return MediaUrl::fromPath($this->cover_image);
        }
        return asset('images/default-event.jpg');
    }

    /**
     * Get formatted event date.
     */
    public function getFormattedEventDateAttribute()
    {
        return $this->event_date->format('d/m/Y');
    }

    /**
     * Get formatted date range.
     */
    public function getDateRangeAttribute()
    {
        if ($this->event_end_date) {
            return $this->event_date->format('d/m/Y') . ' - ' . $this->event_end_date->format('d/m/Y');
        }
        return $this->event_date->format('d/m/Y');
    }
}
