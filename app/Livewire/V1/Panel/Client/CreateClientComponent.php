<?php

declare(strict_types=1);

namespace App\Livewire\V1\Panel\Client;

use Livewire\Component;
use App\Actions\V1\Client\CreateClientAction;
use App\Livewire\Concerns\HandlesActionResults;
use Livewire\WithFileUploads;

class CreateClientComponent extends Component
{
    use HandlesActionResults, WithFileUploads;

    public $name;
    public $last_name;
    public $email;
    public $phone_code;
    public $phone;
    public $license_number;
    public $profile_photo;
    public $status = 'A';

    private $createClientAction;

    public function boot(CreateClientAction $createClientAction)
    {
        $this->createClientAction = $createClientAction;
    }

    public function render()
    {
        return view('v1.panel.client.create-client-component');
    }

    public function createClient()
    {

        $result = $this->executeAction($this->createClientAction, [
            'name' => $this->name,
            'last_name' => $this->last_name,
            'email' => $this->email,
            'phone_code' => $this->phone_code,
            'phone' => $this->phone,
            'license_number' => $this->license_number,
            'status' => $this->status,
        ], true);

        if ($result->isSuccess()) {
            session()->flash('success', __('panel.client_created_successfully'));
            return $this->redirect(route('v1.panel.clients.index'));
        }

        session()->flash('error', __('panel.error_creating_client'));
    }
}
