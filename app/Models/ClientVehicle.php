<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ClientVehicle extends Model
{

    protected $table = 'client_vehicles';

    protected $fillable = [
        'client_id',
        'image_path',
        'year',
        'model_id',
        'vin',
        'buy_date',
        'insurance',
        'make_id',
        'status',
    ];

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function services(): HasMany
    {
        return $this->hasMany(ClientService::class);
    }

    public function make(): BelongsTo
    {
        return $this->belongsTo(VehicleMake::class);
    }

    public function model(): BelongsTo
    {
        return $this->belongsTo(VehicleModel::class);
    }

    public function imagePath(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value == null ? null : Storage::disk('s3')->temporaryUrl($value, Carbon::now()->addMinutes(120)),
            set: fn ($value) => $value == null ? null : Storage::disk('s3')->put('/client_vehicles', $value),
        );
    }

}
