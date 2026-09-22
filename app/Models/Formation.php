<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class Formation extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'short_description',
        'category_id',
        'level',
        'duration_weeks',
        'price',
        'is_internal',
        'target_student_types',
        'student_restriction',
        'registration_start',
        'registration_end',
        'image_url',
        'skills',
        'prerequisites',
        'modules',
        'status',
        'is_featured',
        'published_at',
        'max_students',
        'start_date',
        'end_date',
        'instructor_name',
        'instructor_bio',
        'schedule',
        'format',
        'location',
        'resources',
        'satisfaction_rate',
        'completion_rate',
        'vimeo_code'
    ];

    protected $casts = [
        'skills' => 'array',
        'prerequisites' => 'array',
        'modules' => 'array',
        'resources' => 'array',
        'target_student_types' => 'array',
        'start_date' => 'date',
        'end_date' => 'date',
        'registration_start' => 'date',
        'registration_end' => 'date',
        'published_at' => 'datetime',
        'is_featured' => 'boolean',
        'is_internal' => 'boolean',
        'satisfaction_rate' => 'decimal:2',
        'completion_rate' => 'decimal:2'
    ];

    public function students()
    {
        return $this->belongsToMany(User::class, 'formation_user');
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($formation) {
            if (empty($formation->slug)) {
                $formation->slug = Str::slug($formation->name);
            }
        });

        static::updating(function ($formation) {
            if ($formation->isDirty('name') && empty($formation->slug)) {
                $formation->slug = Str::slug($formation->name);
            }
        });
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function getStudentsCountAttribute()
    {
        return $this->students()->count();
    }

    public function getFormationTypeAttribute()
    {
        return $this->is_internal ? 'Formation Interne EVC' : 'Formation Externe';
    }

    public function getTargetStudentTypesLabelsAttribute()
    {
        if (!$this->target_student_types) {
            return [];
        }

        $labels = [
            'design_graphique' => 'Design Graphique',
            'community_management' => 'Community Management',
            'gestion_informatique' => 'Gestion Informatique',
            'intelligence_artificielle' => 'Intelligence Artificielle'
        ];

        return array_map(function($type) use ($labels) {
            return $labels[$type] ?? $type;
        }, $this->target_student_types);
    }

    public function getStudentRestrictionLabelAttribute()
    {
        $labels = [
            'all' => 'Tous les étudiants',
            'active_only' => 'Étudiants actifs uniquement',
            'registration_period' => 'Période d\'inscription spécifique'
        ];

        return $labels[$this->student_restriction] ?? $this->student_restriction;
    }

    public function isInRegistrationPeriod()
    {
        if ($this->student_restriction !== 'registration_period') {
            return true;
        }

        $now = now();
        return $now->between($this->registration_start, $this->registration_end);
    }

    public function getFormattedPriceAttribute()
    {
        return number_format($this->price, 2) . ' €';
    }

    public function getFormattedDurationAttribute()
    {
        $weeks = $this->duration_weeks;
        if ($weeks == 1) {
            return '1 semaine';
        } elseif ($weeks < 4) {
            return $weeks . ' semaines';
        } else {
            $months = round($weeks / 4, 1);
            return $months . ' mois';
        }
    }

    public function getStatusLabelAttribute()
    {
        $statuses = [
            'draft' => 'Brouillon',
            'active' => 'Active',
            'inactive' => 'Inactive',
            'archived' => 'Archivée'
        ];

        return $statuses[$this->status] ?? $this->status;
    }

    public function getLevelLabelAttribute()
    {
        $levels = [
            'beginner' => 'Débutant',
            'intermediate' => 'Intermédiaire',
            'advanced' => 'Avancé'
        ];

        return $levels[$this->level] ?? $this->level;
    }

    public function getFormatLabelAttribute()
    {
        $formats = [
            'online' => 'En ligne',
            'offline' => 'Présentiel',
            'hybrid' => 'Hybride'
        ];

        return $formats[$this->format] ?? $this->format;
    }

    public function isComplete()
    {
        return !empty($this->name) && 
               !empty($this->description) && 
               !empty($this->category_id) &&
               !empty($this->duration_weeks);
    }

    public function getSimilarFormations($limit = 3)
    {
        return static::where('category_id', $this->category_id)
            ->where('id', '!=', $this->id)
            ->active()
            ->limit($limit)
            ->get();
    }
}
