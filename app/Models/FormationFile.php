<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FormationFile extends Model
{
    use HasFactory;

    protected $fillable = [
        'formation_id',
        'original_name',
        'stored_name',
        'file_path',
        'file_size',
        'mime_type',
        'file_type'
    ];

    /**
     * Relation avec la formation
     */
    public function formation()
    {
        return $this->belongsTo(Formation::class);
    }

    /**
     * Obtenir la taille du fichier formatée
     */
    public function getFormattedSizeAttribute()
    {
        $bytes = $this->file_size;
        if ($bytes >= 1073741824) {
            return number_format($bytes / 1073741824, 2) . ' Go';
        } elseif ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2) . ' Mo';
        } elseif ($bytes >= 1024) {
            return number_format($bytes / 1024, 2) . ' Ko';
        } else {
            return $bytes . ' octets';
        }
    }

    /**
     * Obtenir l'URL complète du fichier
     */
    public function getUrlAttribute()
    {
        return asset($this->file_path);
    }
}
