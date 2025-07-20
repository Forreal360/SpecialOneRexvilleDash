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
            'year' => 'required|integer',
            'make_id' => 'required|exists:makes,id',
            'model_id' => 'required|exists:models,id',
            'vin' => 'required|string',
            'buy_date' => 'required|date',
            'insurance' => 'required|string',
        ]);

        return DB::transaction(function () use ($validated) {

            $this->clientVehicleService->create($validated);

            return $this->successResult();
        });
    }
}
