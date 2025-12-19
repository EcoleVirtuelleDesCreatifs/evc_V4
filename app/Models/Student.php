<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $fillable = [
        'user_id',
        'first_name',
        'last_name',
        // NOTE: 'email' n'existe PAS dans la table students - uniquement dans users
        'phone',
        'whatsapp',
        'age',
        'date_of_birth',
        'gender',
        'student_id',
        'program',
        'level',
        'Level_education',
        'degree',
        'specialization',
        'quartier',
        'address',
        'city',
        'country',
        'biography',
        'profile_photo',
        'status',
        'gpa',
        'credits_earned',
        'years_experience',
        'industry_sector',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'gpa' => 'decimal:2'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function tpAssignments()
    {
        return $this->hasMany(TpAssignment::class, 'student_id');
    }

    public function getFullNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function getProfilePhotoUrlAttribute()
    {
        return $this->profile_photo ? asset('storage/' . $this->profile_photo) : asset('images/default-avatar.png');
    }
}
