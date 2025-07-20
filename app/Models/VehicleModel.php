<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VehicleModel extends Model
{
    protected $table = 'vehicle_models';

    protected $fillable = [
        'name',
        'make_id',
    ];

    public function make()
    {
        return $this->belongsTo(VehicleMake::class, 'make_id');
    }
}
