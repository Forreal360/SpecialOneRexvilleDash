<?php

declare(strict_types=1);

namespace App\Actions\V1\VehicleService;

use App\Actions\V1\Action;
use App\Support\ActionResult;
use Illuminate\Support\Facades\DB;
use App\Services\V1\VehicleServiceService;

class UpdateVehicleServiceAction extends Action
{
    public function __construct(private VehicleServiceService $vehicleServiceService)
    {

    }

    public function handle($data): ActionResult
    {
        $validated = $this->validateData($data, [
            "id" => "required|integer|exists:vehicle_services,id",
            "name" => "required|string|max:255|unique:vehicle_services,name," . $data['id'],
            "status" => "required|string|in:A,I",
        ]);

        return DB::transaction(function () use ($validated) {
            $vehicleServiceId = $validated['id'];

            unset($validated['id']);

            $this->vehicleServiceService->update((int) $vehicleServiceId, $validated);

            return $this->successResult();
        });
    }
}