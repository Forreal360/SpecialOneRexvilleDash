<?php

namespace App\Livewire\V1\Panel\Admin;

use Livewire\Component;
use App\Actions\V1\Admin\CreateAdminAction;
use App\Livewire\Concerns\HandlesActionResults;

class CreateAdminComponent extends Component
{

    use HandlesActionResults;

    public $name;
    public $last_name;
    public $email;
    public $password;
    public $password_confirmation;
    public $status;

    private $createAdminAction;

    public function boot(CreateAdminAction $createAdminAction){
        $this->createAdminAction = $createAdminAction;
    }

    public function render()
    {
        return view('v1.panel.admin.create-admin-component');
    }

    public function createAdmin()
    {

        $createAdminResult = $this->executeAction($this->createAdminAction, [
            'name' => $this->name,
            'last_name' => $this->last_name,
            'email' => $this->email,
            'password' => $this->password,
            'password_confirmation' => $this->password_confirmation,
        ], true);

        if ($createAdminResult->isSuccess()) {
            session()->flash('success', __('panel.admin_created_successfully'));
            return $this->redirect(route('v1.panel.admins.index'), navigate: true);
        }

        session()->flash('error', __('panel.error_creating_admin'));
    }
}
