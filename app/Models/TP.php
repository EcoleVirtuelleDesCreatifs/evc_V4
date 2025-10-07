<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TP extends Model
{
    use HasFactory;

    protected $table = 'tp';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'title',
        'description',
        'link',
        'status',
        'admin_comment',
        'validated_at'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'validated_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the user that owns the TP.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the files for the TP.
     */
    public function files()
    {
        return $this->hasMany(TPFile::class, 'tp_id');
    }
}
