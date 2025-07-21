<?php

declare(strict_types=1);

namespace App\Livewire\V1\Panel\VehicleService;

use Livewire\Component;
use App\Actions\V1\VehicleService\CreateVehicleServiceAction;
use App\Livewire\Concerns\HandlesActionResults;

class CreateVehicleServiceComponent extends Component
{
    use HandlesActionResults;

    public $name = '';
    public $status = 'A';

    private $createVehicleServiceAction;

    public function boot(CreateVehicleServiceAction $createVehicleServiceAction)
    {
        $this->createVehicleServiceAction = $createVehicleServiceAction;
    }

    public function render()
    {
        return view('v1.panel.vehicle-service.create-vehicle-service-component');
    }

    public function createVehicleService()
    {
        $result = $this->executeAction($this->createVehicleServiceAction, [
            'name' => $this->name,
            'status' => $this->status,
        ], true);

        if ($result->isSuccess()) {
            session()->flash('success', __('panel.vehicle_service_created_successfully'));
            return $this->redirect(route('v1.panel.vehicle-services.index'));
        }

        session()->flash('error', __('panel.error_creating_vehicle_service'));
    }

    public function resetForm()
    {
        $this->reset(['name', 'status']);
        $this->status = 'A';
    }
} 