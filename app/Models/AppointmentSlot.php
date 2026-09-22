<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AppointmentSlot extends Model
{
    protected $fillable = [
        'admin_id', 'date', 'start_time', 'end_time',
        'mode', 'lieu', 'capacity', 'is_active',
    ];

    protected $casts = [
        'date' => 'date',
        'is_active' => 'boolean',
    ];

    public function appointments()
    {
        return $this->hasMany(Appointment::class, 'slot_id');
    }

    public function activeAppointments()
    {
        return $this->hasMany(Appointment::class, 'slot_id')
            ->whereIn('status', ['pending', 'confirmed']);
    }

    public function bookedCount(): int
    {
        return $this->activeAppointments()->count();
    }

    public function remainingCapacity(): int
    {
        return max(0, (int) $this->capacity - $this->bookedCount());
    }

    public function isBookable(): bool
    {
        if (!$this->is_active) {
            return false;
        }
        return $this->remainingCapacity() > 0;
    }

    public function isPast(): bool
    {
        return $this->date->isPast() && !$this->date->isToday();
    }

    public function durationMinutes(): int
    {
        $start = \Carbon\Carbon::parse($this->start_time);
        $end = \Carbon\Carbon::parse($this->end_time);
        return $start->diffInMinutes($end);
    }
}
