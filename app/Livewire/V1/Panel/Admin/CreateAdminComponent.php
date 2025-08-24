<?php

namespace App\Livewire\V1\Panel\Admin;

use Livewire\Component;
use App\Actions\V1\Admin\CreateAdminAction;
use App\Livewire\Concerns\HandlesActionResults;
use App\Models\Role;

class CreateAdminComponent extends Component
{

    use HandlesActionResults;

    public $name;
    public $last_name;
    public $email;
    public $password;
    public $password_confirmation;
    public $status;
    public $selectedRole = null;

    private $createAdminAction;

    public function boot(CreateAdminAction $createAdminAction){
        $this->createAdminAction = $createAdminAction;
    }

    public function render()
    {
        $roles = Role::where('guard_name', 'admin')->get();
        return view('v1.panel.admin.create-admin-component', compact('roles'));
    }

    public function createAdmin()
    {

        $createAdminResult = $this->executeAction($this->createAdminAction, [
            'name' => $this->name,
            'last_name' => $this->last_name,
            'email' => $this->email,
            'password' => $this->password,
            'password_confirmation' => $this->password_confirmation,
            'role' => $this->selectedRole,
        ], true);

        if ($createAdminResult->isSuccess()) {
            session()->flash('success', __('panel.admin_created_successfully'));
            return redirect()->route('v1.panel.admins.index');
        }

        session()->flash('error', $createAdminResult->getMessage());
    }
}
