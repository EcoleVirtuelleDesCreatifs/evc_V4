<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DesignProjectFile extends Model
{
    use HasFactory;

    protected $table = 'design_project_files';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'project_id',
        'original_name',
        'stored_name',
        'file_path',
        'file_size',
        'mime_type',
        'file_category'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'file_size' => 'integer',
        'created_at' => 'datetime',
    ];

    /**
     * File category labels for display
     */
    const CATEGORY_LABELS = [
        'source' => 'Source',
        'preview' => 'Aperçu',
        'final' => 'Final',
        'reference' => 'Référence'
    ];

    /**
     * Get the design project that owns the file.
     */
    public function designProject()
    {
        return $this->belongsTo(DesignProject::class, 'project_id');
    }

    /**
     * Get category label.
     */
    public function getCategoryLabelAttribute()
    {
        return self::CATEGORY_LABELS[$this->file_category] ?? $this->file_category;
    }

    /**
     * Get formatted file size.
     */
    public function getFormattedSizeAttribute()
    {
        $bytes = $this->file_size;

        if ($bytes >= 1073741824) {
            return number_format($bytes / 1073741824, 2) . ' GB';
        } elseif ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            return number_format($bytes / 1024, 2) . ' KB';
        } else {
            return $bytes . ' B';
        }
    }

    /**
     * Check if file is an image.
     */
    public function getIsImageAttribute()
    {
        return str_starts_with($this->mime_type, 'image/');
    }

    /**
     * Get file extension.
     */
    public function getExtensionAttribute()
    {
        return strtoupper(pathinfo($this->original_name, PATHINFO_EXTENSION));
    }

    /**
     * Scope for images only.
     */
    public function scopeImages($query)
    {
        return $query->where('mime_type', 'like', 'image/%');
    }

    /**
     * Scope for preview files.
     */
    public function scopePreview($query)
    {
        return $query->where('file_category', 'preview');
    }

    /**
     * Scope for final files.
     */
    public function scopeFinal($query)
    {
        return $query->where('file_category', 'final');
    }
}
