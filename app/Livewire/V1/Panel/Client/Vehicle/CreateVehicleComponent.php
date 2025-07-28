<?php

declare(strict_types=1);

namespace App\Livewire\V1\Panel\Client\Vehicle;

use Livewire\Component;
use App\Actions\V1\Client\Vehicle\CreateVehicleAction;
use App\Livewire\Concerns\HandlesActionResults;
use App\Models\VehicleMake;
use App\Models\VehicleModel;
use Livewire\WithFileUploads;
use Carbon\Carbon;

class CreateVehicleComponent extends Component
{
    use HandlesActionResults, WithFileUploads;

    public $clientId;
    public $year;
    public $make_id;
    public $model_id;
    public $vin;
    public $buy_date;
    public $insurance;
    public $status = 'A';
    public $image;
    private $createVehicleAction;

    public function mount($clientId)
    {
        $this->clientId = $clientId;
    }

    public function boot(CreateVehicleAction $createVehicleAction)
    {
        $this->createVehicleAction = $createVehicleAction;
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

        return view('v1.panel.client.vehicle.create-vehicle-component', compact('makes', 'models'));
    }

    public function updatedMakeId()
    {
        $this->model_id = '';
    }

    public function createVehicle()
    {

        $this->buy_date = Carbon::parse($this->buy_date)->format('Y-m-d');

        $result = $this->executeAction($this->createVehicleAction, [
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
            session()->flash('success', __('panel.vehicle_created_successfully'));
            return $this->redirect(route('v1.panel.vehicles.index', $this->clientId));
        }

        session()->flash('error', __('panel.error_creating_vehicle'));
    }
} 