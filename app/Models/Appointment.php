<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    protected $fillable = [
        'slot_id', 'user_id', 'student_id', 'motif', 'message',
        'status', 'meet_link', 'admin_note',
    ];

    public function slot()
    {
        return $this->belongsTo(AppointmentSlot::class, 'slot_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function isCancellable(): bool
    {
        if ($this->status !== 'pending') {
            return false;
        }
        return $this->slot && !$this->slot->isPast();
    }
}
