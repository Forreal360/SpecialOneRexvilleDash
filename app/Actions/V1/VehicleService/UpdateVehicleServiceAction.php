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

    /**
     * Handle the action logic
     *
     * @param array|object $data
     * @return ActionResult
     */
    public function handle($data): ActionResult
    {

        $this->validatePermissions([
            "vehicle-services.update"
        ]);


        $validated = $this->validateData($data, [
            'vehicle_service_id' => 'required|exists:vehicle_services,id',
            'name' => 'required|string|max:255|unique:vehicle_services,name,' . $data['vehicle_service_id'] ?? '',
            'status' => 'required|in:A,I',
        ]);

        return DB::transaction(function () use ($validated) {

            $this->vehicleServiceService->update((int) $validated['vehicle_service_id'], $validated);

            return $this->successResult();
        });
    }
}
