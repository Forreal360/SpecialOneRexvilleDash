<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AppointmentService extends Model
{
    protected $fillable = [
        'appointment_id',
        'service_id',
        'status',
    ];

    protected $casts = [
        'status' => 'string',
    ];

    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }

    public function service()
    {
        return $this->belongsTo(VehicleService::class);
    }

    public function isActive(): bool
    {
        return $this->status === 'A';
    }

    public function isInactive(): bool
    {
        return $this->status === 'I';
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'A');
    }

    public function scopeInactive($query)
    {
        return $query->where('status', 'I');
    }
}
