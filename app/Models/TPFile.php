<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TPFile extends Model
{
    use HasFactory;

    protected $table = 'tp_files';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'tp_id',
        'original_name',
        'file_path',
        'file_size',
        'mime_type'
    ];

    /**
     * Get the TP that owns the file.
     */
    public function tp()
    {
        return $this->belongsTo(TP::class, 'tp_id');
    }
}
