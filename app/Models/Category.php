<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'module',
        'status'
    ];

    /**
     * Relation avec les formations
     */
    public function formations()
    {
        return $this->hasMany(Formation::class);
    }

    /**
     * Générer automatiquement le slug
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($category) {
            if (empty($category->slug)) {
                $category->slug = Str::slug($category->name);
            }
        });

        static::updating(function ($category) {
            if ($category->isDirty('name') && empty($category->slug)) {
                $category->slug = Str::slug($category->name);
            }
        });
    }

    /**
     * Scope pour les catégories actives
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Obtenir le nombre de formations dans cette catégorie
     */
    public function getFormationsCountAttribute()
    {
        return $this->formations()->count();
    }

    /**
     * Obtenir le nombre d'étudiants dans cette catégorie
     */
    public function getStudentsCountAttribute()
    {
        return $this->formations()
            ->get()
            ->sum(function ($formation) {
                return $formation->students_count;
            });
    }
}
