<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TpAssignment extends Model
{
    protected $table = 'tp_assignments';

    protected $fillable = [
        'student_id',
        'title',
        'description',
        'deadline',
        'status',
        'submission_link',
        'admin_comment',
        'validated_at',
        'formation',
    ];

    protected $casts = [
        'deadline' => 'datetime',
        'validated_at' => 'datetime',
    ];

    /**
     * Relation avec Student
     */
    public function student()
    {
        return $this->belongsTo(\App\Models\Student::class, 'student_id');
    }

    /**
     * Relation avec les fichiers de soumission
     */
    public function files()
    {
        return $this->hasMany(\App\Models\TpSubmissionFile::class, 'tp_assignment_id');
    }

    /**
     * Accesseur pour obtenir l'utilisateur via le student
     */
    public function getUserAttribute()
    {
        return $this->student ? $this->student->user : null;
    }
}
