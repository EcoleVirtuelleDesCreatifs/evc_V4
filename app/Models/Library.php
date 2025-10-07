<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Library extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'title', 'path', 'pdf_path', 'download_url', 'file_type', 'size', 'downloads_count', 'library_category_id', 'user_id', 'recipients', 'status'];

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

}
