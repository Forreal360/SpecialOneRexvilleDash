<?php

declare(strict_types=1);

namespace App\Actions\V1\Client\Vehicle;

use App\Actions\V1\Action;
use App\Support\ActionResult;
use Illuminate\Support\Facades\DB;
use App\Services\V1\ClientVehicleService;

class CreateVehicleAction extends Action
{

    public function __construct(private ClientVehicleService $clientVehicleService)
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

        $validated = $this->validateData($data, [
            'client_id' => 'required|exists:clients,id',
            'year' => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'make_id' => 'required|exists:vehicle_makes,id',
            'model_id' => 'required|exists:vehicle_models,id',
            'vin' => 'required|string|max:255',
            'buy_date' => 'required|date',
            'insurance' => 'required|string|max:255',
            'status' => 'required|in:A,I',
        ]);


        return DB::transaction(function () use ($validated) {

            $this->clientVehicleService->create($validated);

            return $this->successResult();
        });
    }
}
