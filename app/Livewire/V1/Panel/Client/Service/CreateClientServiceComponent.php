<?php

declare(strict_types=1);

namespace App\Livewire\V1\Panel\Client\Service;

use Livewire\Component;
use App\Actions\V1\Client\Service\CreateClientServiceAction;
use App\Livewire\Concerns\HandlesActionResults;
use App\Models\ClientVehicle;
use App\Models\VehicleService;

class CreateClientServiceComponent extends Component
{
    use HandlesActionResults;

    public $clientId;
    public $vehicle_id = '';
    public $service_id = '';
    public $date = '';

    private $createClientServiceAction;

    public function mount($clientId)
    {
        $this->clientId = $clientId;
    }

    public function boot(CreateClientServiceAction $createClientServiceAction)
    {
        $this->createClientServiceAction = $createClientServiceAction;
    }

    public function render()
    {
        $vehicles = ClientVehicle::where('client_id', $this->clientId)
            ->where('status', 'A')
            ->with(['make', 'model'])
            ->orderBy('year', 'desc')
            ->get();

        $services = VehicleService::where('status', 'A')
            ->orderBy('name')
            ->get();

        return view('v1.panel.client.service.create-client-service-component', compact('vehicles', 'services'));
    }

    public function createClientService()
    {
        $result = $this->executeAction($this->createClientServiceAction, [
            'client_id' => $this->clientId,
            'vehicle_id' => $this->vehicle_id,
            'service_id' => $this->service_id,
            'date' => $this->date,
        ], true);

        if ($result->isSuccess()) {
            session()->flash('success', __('panel.client_service_created_successfully'));
            return $this->redirect(route('v1.panel.client-services.index', $this->clientId));
        }

        session()->flash('error', __('panel.error_creating_client_service'));
    }

    public function resetForm()
    {
        $this->reset(['vehicle_id', 'service_id', 'date']);
    }
} 