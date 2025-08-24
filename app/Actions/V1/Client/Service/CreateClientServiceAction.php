<?php

declare(strict_types=1);

namespace App\Actions\V1\Client\Service;

use App\Actions\V1\Action;
use App\Support\ActionResult;
use Illuminate\Support\Facades\DB;
use App\Services\V1\ClientServiceService;

class CreateClientServiceAction extends Action
{

    public function __construct(private ClientServiceService $clientServiceService)
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
            "clients-vehicles-services.create"
        ]);

        $validated = $this->validateData($data, [
            'client_id' => 'required|exists:clients,id',
            'vehicle_id' => 'required|exists:client_vehicles,id',
            'service_id' => 'required|exists:vehicle_services,id',
            'date' => 'required|date',
        ]);

        $date = \Carbon\Carbon::createFromFormat('m/d/Y', $validated['date']);
        $validated['date'] = $date->format('Y-m-d');


        return DB::transaction(function () use ($validated) {

            $this->clientServiceService->create($validated);

            return $this->successResult();
        });
    }
}
