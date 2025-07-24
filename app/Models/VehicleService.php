<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class VehicleService extends Model
{
    use HasFactory;

    protected $table = 'vehicle_services';

    protected $fillable = [
        'name',
        'status',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', 'A');
    }

    public function scopeInactive(Builder $query): Builder
    {
        return $query->where('status', 'I');
    }

    public function isActive(): bool
    {
        return $this->status === 'A';
    }

    public function getStatusTextAttribute(): string
    {
        return $this->status === 'A' ? __('panel.active') : __('panel.inactive');
    }
}
