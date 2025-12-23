<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class Library extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'title', 'path', 'pdf_path', 'download_url', 'file_type', 'size', 'downloads_count', 'library_category_id', 'user_id', 'recipients', 'status', 'is_featured', 'cover_image', 'external_link'];

    protected $casts = [
        'recipients' => 'array',
    ];

    public function libraryCategory()
    {
        return $this->belongsTo(LibraryCategory::class);
    }

    /**
     * Obtenir l'utilisateur qui a téléversé le document.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getCoverImageUrlAttribute()
    {
        $path = (string) ($this->cover_image ?? '');
        if ($path === '') {
            $path = (string) ($this->path ?? '');
        }

        if ($path === '') {
            return null;
        }

        if (Str::startsWith($path, ['http://', 'https://'])) {
            return $path;
        }

        $path = ltrim($path, '/');
        if (Str::startsWith($path, 'storage/')) {
            $path = Str::after($path, 'storage/');
        }

        return Storage::disk('public')->url($path);
    }

    public function getFileUrlAttribute()
    {
        $path = (string) ($this->path ?? '');
        if ($path === '') {
            return null;
        }

        if (Str::startsWith($path, ['http://', 'https://'])) {
            return $path;
        }

        $path = ltrim($path, '/');
        if (Str::startsWith($path, 'storage/')) {
            $path = Str::after($path, 'storage/');
        }

        return asset('storage/' . $path);
    }

    public function getPdfUrlAttribute()
    {
        $path = (string) ($this->pdf_path ?? '');
        if ($path === '') {
            return null;
        }

        if (Str::startsWith($path, ['http://', 'https://'])) {
            return $path;
        }

        $path = ltrim($path, '/');
        if (Str::startsWith($path, 'storage/')) {
            $path = Str::after($path, 'storage/');
        }

        return asset('storage/' . $path);
    }

}
