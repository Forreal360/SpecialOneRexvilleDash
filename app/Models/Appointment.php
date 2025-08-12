<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;

class Appointment extends Model
{
    use HasFactory;

    protected $table = 'appointments';

    protected $fillable = [
        'client_id',
        'vehicle_id',
        'service_id',
        'appointment_datetime',
        'timezone',
        'status',
        'notes',
    ];

    protected $casts = [
        'appointment_datetime' => 'datetime',
    ];

    /**
     * Get the client that owns the appointment.
     */
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    /**
     * Get the vehicle associated with the appointment.
     */
    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(ClientVehicle::class, 'vehicle_id');
    }

    /**
     * Get the service associated with the appointment.
     */
    public function services(): BelongsToMany
    {
        return $this->belongsToMany(VehicleService::class, 'appointment_services', 'appointment_id', 'service_id')
            ->withPivot('status')
            ->withTimestamps();
    }

    public function appointmentServices(): HasMany
    {
        return $this->hasMany(AppointmentService::class, 'appointment_id', 'id');
    }

    /**
     * Get only active services for the appointment.
     */
    public function activeServices(): BelongsToMany
    {
        return $this->services()->wherePivot('status', 'A');
    }

    /**
     * Get only inactive services for the appointment.
     */
    public function inactiveServices(): BelongsToMany
    {
        return $this->services()->wherePivot('status', 'I');
    }

    /**
     * Scope to filter by status
     */
    public function scopeByStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope to filter by date range
     */
    public function scopeDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('appointment_datetime', [$startDate, $endDate]);
    }

    /**
     * Scope to filter by client
     */
    public function scopeByClient($query, int $clientId)
    {
        return $query->where('client_id', $clientId);
    }

    /**
     * Get formatted appointment datetime with timezone
     */
    public function getFormattedDateTimeAttribute(): string
    {
        return Carbon::parse($this->appointment_datetime)
            ->setTimezone($this->timezone)
            ->format('d/m/Y H:i');
    }

    /**
     * Check if appointment is pending
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    /**
     * Check if appointment is confirmed
     */
    public function isConfirmed(): bool
    {
        return $this->status === 'confirmed';
    }

    /**
     * Check if appointment is cancelled
     */
    public function isCancelled(): bool
    {
        return $this->status === 'cancelled';
    }

    /**
     * Check if appointment is completed
     */
    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }
}
