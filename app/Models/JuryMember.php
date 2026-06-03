<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class JuryMember extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'unique_identifier',
        'title',
        'country',
        'flag',
        'image_path',
        'image_url',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function getPhotoUrlAttribute(): string
    {
        if (!empty($this->image_url)) {
            return $this->image_url;
        }

        if (!empty($this->image_path)) {
            return Storage::url($this->image_path);
        }

        return asset('assets/img/default-avatar.png');
    }

    public function evaluations()
    {
        return $this->hasMany(JuryEvaluation::class);
    }
}
