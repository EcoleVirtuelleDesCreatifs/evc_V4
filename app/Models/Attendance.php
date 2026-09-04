<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Attendance extends Model
{
    use HasFactory;

    protected $table = 'attendances';

    protected $fillable = [
        'seance_id',
        'student_id',
        'user_id',
        'status',
        'check_method',
        'recorded_by',
        'recorded_at',
        'check_in_at',
        'notes',
    ];

    protected $casts = [
        'recorded_at' => 'datetime',
        'check_in_at' => 'datetime',
    ];

    public function seance(): BelongsTo
    {
        return $this->belongsTo(Seance::class, 'seance_id');
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

}
