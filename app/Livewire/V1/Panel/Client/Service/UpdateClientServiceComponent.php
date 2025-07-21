<?php

declare(strict_types=1);

namespace App\Livewire\V1\Panel\Client\Service;

use Livewire\Component;
use App\Actions\V1\Client\Service\UpdateClientServiceAction;
use App\Livewire\Concerns\HandlesActionResults;
use App\Models\ClientVehicle;
use App\Models\VehicleService;
use App\Models\ClientService;

class UpdateClientServiceComponent extends Component
{
    use HandlesActionResults;

    public $clientId;
    public $clientServiceId;
    public $vehicle_id = '';
    public $service_id = '';
    public $date = '';

    private $updateClientServiceAction;

    public function mount($clientId, $id)
    {
        $this->clientId = $clientId;
        $this->clientServiceId = $id;
        
        $this->loadClientService();
    }

    public function boot(UpdateClientServiceAction $updateClientServiceAction)
    {
        $this->updateClientServiceAction = $updateClientServiceAction;
    }

    private function loadClientService()
    {
        $clientService = ClientService::findOrFail($this->clientServiceId);
        
        $this->vehicle_id = $clientService->vehicle_id;
        $this->service_id = $clientService->service_id;
        $this->date = $clientService->date ? \Carbon\Carbon::parse($clientService->date)->format('m/d/Y') : null;
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

        return view('v1.panel.client.service.update-client-service-component', compact('vehicles', 'services'));
    }

    public function updateClientService()
    {
        $result = $this->executeAction($this->updateClientServiceAction, [
            'client_service_id' => $this->clientServiceId,
            'client_id' => $this->clientId,
            'vehicle_id' => $this->vehicle_id,
            'service_id' => $this->service_id,
            'date' => $this->date,
        ], true);

        if ($result->isSuccess()) {
            session()->flash('success', __('panel.client_service_updated_successfully'));
            return $this->redirect(route('v1.panel.client-services.index', $this->clientId));
        }

        session()->flash('error', __('panel.error_updating_client_service'));
    }
} 