<?php

namespace App\Livewire\V1\Panel\VehicleService;

use Livewire\Component;
use App\Actions\V1\VehicleService\UpdateVehicleServiceAction;
use App\Livewire\Concerns\HandlesActionResults;
use App\Services\V1\VehicleServiceService;

class UpdateVehicleServiceComponent extends Component
{
    use HandlesActionResults;

    public $vehicle_service_id;
    public $name;
    public $status;

    private $updateVehicleServiceAction;
    private $vehicleServiceService;

    public function boot(UpdateVehicleServiceAction $updateVehicleServiceAction, VehicleServiceService $vehicleServiceService)
    {
        $this->updateVehicleServiceAction = $updateVehicleServiceAction;
        $this->vehicleServiceService = $vehicleServiceService;
    }

    public function mount($id)
    {
        $this->vehicle_service_id = $id;
        $this->loadVehicleService();
    }

    public function loadVehicleService()
    {
        $vehicleService = $this->vehicleServiceService->findByIdOrFail($this->vehicle_service_id);

        $this->name = $vehicleService->name;
        $this->status = $vehicleService->status;
    }

    public function render()
    {
        return view('v1.panel.vehicle-service.update-vehicle-service-component');
    }

    public function updateVehicleService()
    {
        $data = [
            'id' => $this->vehicle_service_id,
            'name' => $this->name,
            'status' => $this->status,
        ];

        $updateVehicleServiceResult = $this->executeAction($this->updateVehicleServiceAction, $data, true);

        if ($updateVehicleServiceResult->isSuccess()) {
            session()->flash('success', __('panel.vehicle_service_updated_successfully'));
            return $this->redirect(route('v1.panel.vehicle-services.index'));
        }

        session()->flash('error', __('panel.error_updating_vehicle_service'));
    }

    public function cancel()
    {
        return $this->redirect(route('v1.panel.vehicle-services.index'));
    }
}