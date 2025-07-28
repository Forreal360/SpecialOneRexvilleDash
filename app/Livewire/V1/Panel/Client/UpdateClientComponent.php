<?php

declare(strict_types=1);

namespace App\Livewire\V1\Panel\Client;

use Livewire\Component;
use App\Actions\V1\Client\UpdateClientAction;
use App\Services\V1\ClientService;
use App\Livewire\Concerns\HandlesActionResults;
use Livewire\WithFileUploads;

class UpdateClientComponent extends Component
{
    use HandlesActionResults, WithFileUploads;

    public $client_id;
    public $name;
    public $last_name;
    public $email;
    public $phone_code;
    public $phone;
    public $license_number;
    public $profile_photo;
    public $status = 'A';

    private $updateClientAction;
    private $clientService;

    public function boot(UpdateClientAction $updateClientAction, ClientService $clientService)
    {
        $this->updateClientAction = $updateClientAction;
        $this->clientService = $clientService;
    }

    public function mount($id)
    {
        $this->client_id = (int) $id;
        $this->loadClient();
    }

    public function loadClient()
    {
        $client = $this->clientService->findById((int) $this->client_id);

        if ($client) {
            $this->name = $client->name;
            $this->last_name = $client->last_name;
            $this->email = $client->email;
            $this->phone_code = $client->phone_code;
            $this->phone = $client->phone;
            $this->license_number = $client->license_number;
            $this->status = $client->status;
        }
    }

    public function render()
    {
        return view('v1.panel.client.update-client-component');
    }

    public function updateClient()
    {
        $this->phone_code = str_replace('+', '', $this->phone_code);
        
        $result = $this->executeAction($this->updateClientAction, [
            'id' => $this->client_id,
            'name' => $this->name,
            'last_name' => $this->last_name,
            'email' => $this->email,
            'phone_code' => $this->phone_code,
            'phone' => $this->phone,
            'license_number' => $this->license_number,
            'status' => $this->status,
        ], true);

        if ($result->isSuccess()) {
            session()->flash('success', __('panel.client_updated_successfully'));
            return $this->redirect(route('v1.panel.clients.index'));
        }

        session()->flash('error', __('panel.error_updating_client'));
    }
}
