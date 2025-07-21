<?php

declare(strict_types=1);

namespace App\Livewire\V1\Panel\VehicleService;

use Livewire\Component;
use App\Actions\V1\VehicleService\UpdateVehicleServiceAction;
use App\Livewire\Concerns\HandlesActionResults;
use App\Models\VehicleService;

class UpdateVehicleServiceComponent extends Component
{
    use HandlesActionResults;

    public $vehicleServiceId;
    public $name = '';
    public $status = 'A';

    private $updateVehicleServiceAction;

    public function mount($id)
    {
        $this->vehicleServiceId = $id;
        $this->loadVehicleService();
    }

    public function boot(UpdateVehicleServiceAction $updateVehicleServiceAction)
    {
        $this->updateVehicleServiceAction = $updateVehicleServiceAction;
    }

    private function loadVehicleService()
    {
        $vehicleService = VehicleService::findOrFail($this->vehicleServiceId);
        
        $this->name = $vehicleService->name;
        $this->status = $vehicleService->status;
    }

    public function render()
    {
        return view('v1.panel.vehicle-service.update-vehicle-service-component');
    }

    public function updateVehicleService()
    {
        $result = $this->executeAction($this->updateVehicleServiceAction, [
            'vehicle_service_id' => $this->vehicleServiceId,
            'name' => $this->name,
            'status' => $this->status,
        ], true);

        if ($result->isSuccess()) {
            session()->flash('success', __('panel.vehicle_service_updated_successfully'));
            return $this->redirect(route('v1.panel.vehicle-services.index'));
        }

        session()->flash('error', __('panel.error_updating_vehicle_service'));
    }
} 