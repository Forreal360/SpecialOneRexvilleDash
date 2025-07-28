<?php

declare(strict_types=1);

namespace App\Livewire\V1\Panel\Client\Vehicle;

use Livewire\Component;
use App\Actions\V1\Client\Vehicle\UpdateVehicleAction;
use App\Livewire\Concerns\HandlesActionResults;
use App\Models\VehicleMake;
use App\Models\VehicleModel;
use App\Models\ClientVehicle;
use Livewire\WithFileUploads;
use Carbon\Carbon;

class UpdateVehicleComponent extends Component
{
    use HandlesActionResults, WithFileUploads;

    public $clientId;
    public $vehicleId;
    public $year;
    public $make_id;
    public $model_id;
    public $vin;
    public $buy_date;
    public $insurance;
    public $status = 'A';
    public $image;

    private $updateVehicleAction;

    public function mount($clientId, $id)
    {
        $this->clientId = $clientId;
        $this->vehicleId = $id;
        
        $this->loadVehicle();
    }

    public function boot(UpdateVehicleAction $updateVehicleAction)
    {
        $this->updateVehicleAction = $updateVehicleAction;
    }

    private function loadVehicle()
    {
        $vehicle = ClientVehicle::findOrFail($this->vehicleId);
        
        $this->year = $vehicle->year;
        $this->make_id = $vehicle->make_id;
        $this->model_id = $vehicle->model_id;
        $this->vin = $vehicle->vin;
        $this->buy_date = $vehicle->buy_date ? \Carbon\Carbon::parse($vehicle->buy_date)->format('m/d/Y') : null;
        $this->insurance = $vehicle->insurance;
        $this->status = $vehicle->status;
    }

    public function render()
    {
        $makes = VehicleMake::orderBy('name')->get();
        $models = collect();

        if ($this->make_id) {
            $models = VehicleModel::where('make_id', $this->make_id)
                ->orderBy('name')
                ->get();
        }

        return view('v1.panel.client.vehicle.update-vehicle-component', compact('makes', 'models'));
    }

    public function updatedMakeId()
    {
        $this->model_id = '';
    }

    public function updateVehicle()
    {
        $this->buy_date = Carbon::parse($this->buy_date)->format('Y-m-d');

        $result = $this->executeAction($this->updateVehicleAction, [
            'vehicle_id' => $this->vehicleId,
            'client_id' => $this->clientId,
            'year' => $this->year,
            'make_id' => $this->make_id,
            'model_id' => $this->model_id,
            'vin' => $this->vin,
            'buy_date' => $this->buy_date,
            'insurance' => $this->insurance,
            'status' => $this->status,
            'image' => $this->image,
        ], true);

        if ($result->isSuccess()) {
            session()->flash('success', __('panel.vehicle_updated_successfully'));
            return $this->redirect(route('v1.panel.vehicles.index', $this->clientId));
        }

        session()->flash('error', __('panel.error_updating_vehicle'));
    }
} 