<?php

declare(strict_types=1);

namespace App\Actions\V1\VehicleService;

use App\Actions\V1\Action;
use App\Support\ActionResult;
use Illuminate\Support\Facades\DB;
use App\Services\V1\VehicleServiceService;

class CreateVehicleServiceAction extends Action
{
    public function __construct(private VehicleServiceService $vehicleServiceService)
    {

    }

    public function handle($data): ActionResult
    {
        $validated = $this->validateData($data, [
            "name" => "required|string|max:255|unique:vehicle_services,name",
            "status" => "required|string|in:A,I",
        ]);

        return DB::transaction(function () use ($validated) {
            $this->vehicleServiceService->create($validated);

            return $this->successResult();
        });
    }
}