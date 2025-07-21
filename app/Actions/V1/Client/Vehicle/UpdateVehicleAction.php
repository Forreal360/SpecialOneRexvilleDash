<?php

declare(strict_types=1);

namespace App\Actions\V1\Client\Vehicle;

use App\Actions\V1\Action;
use App\Support\ActionResult;
use Illuminate\Support\Facades\DB;
use App\Services\V1\ClientVehicleService;

class UpdateVehicleAction extends Action
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
            'vehicle_id' => 'required|exists:client_vehicles,id',
            'client_id' => 'required|exists:clients,id',
            'year' => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'make_id' => 'required|exists:vehicle_makes,id',
            'model_id' => 'required|exists:vehicle_models,id',
            'vin' => 'required|string|max:255',
            'buy_date' => 'required|date',
            'insurance' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'status' => 'required|in:A,I',
        ]);

        if (isset($validated['image'])) {
            $validated['image_path'] = $validated['image'];
            unset($validated['image']);
        }

        return DB::transaction(function () use ($validated) {

            $this->clientVehicleService->update((int) $validated['vehicle_id'], $validated);

            return $this->successResult();
        });
    }
} 