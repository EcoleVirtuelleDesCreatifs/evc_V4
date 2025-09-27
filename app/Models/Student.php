<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone',
        'date_of_birth',
        'gender',
        'student_id',
        'program',
        'level',
        'specialization',
        'address',
        'city',
        'country',
        'profile_photo',
        'status',
        'gpa',
        'credits_earned'
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'gpa' => 'decimal:2'
    ];

    public function getFullNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function getProfilePhotoUrlAttribute()
    {
        return $this->profile_photo ? asset('storage/' . $this->profile_photo) : asset('images/default-avatar.png');
    }
}
