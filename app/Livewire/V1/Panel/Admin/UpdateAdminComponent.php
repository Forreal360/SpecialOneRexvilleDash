<?php

namespace App\Livewire\V1\Panel\Admin;

use Livewire\Component;
use App\Actions\V1\Admin\UpdateAdminAction;
use App\Livewire\Concerns\HandlesActionResults;
use App\Services\V1\AdminService;

class UpdateAdminComponent extends Component
{
    use HandlesActionResults;

    public $admin_id;
    public $name;
    public $last_name;
    public $email;
    public $password;
    public $password_confirmation;
    public $status;

    private $updateAdminAction;
    private $adminService;

    public function boot(UpdateAdminAction $updateAdminAction, AdminService $adminService)
    {
        $this->updateAdminAction = $updateAdminAction;
        $this->adminService = $adminService;
    }

    public function mount($id)
    {
        $this->admin_id = $id;
        $this->loadAdmin();
    }

    public function loadAdmin()
    {
        $admin = $this->adminService->findByIdOrFail($this->admin_id);

        $this->name = $admin->name;
        $this->last_name = $admin->last_name;
        $this->email = $admin->email;
        $this->status = $admin->status;
    }

    public function render()
    {
        return view('v1.panel.admin.update-admin-component');
    }

    public function updateAdmin()
    {
        $data = [
            'id' => $this->admin_id,
            'name' => $this->name,
            'last_name' => $this->last_name,
            'email' => $this->email,
            'status' => $this->status,
        ];

        // Solo incluir password si se proporciona
        if (!empty($this->password)) {
            $data['password'] = $this->password;
            $data['password_confirmation'] = $this->password_confirmation;
        }

        $updateAdminResult = $this->executeAction($this->updateAdminAction, $data, true);

        if ($updateAdminResult->isSuccess()) {
            session()->flash('success', __('panel.admin_updated_successfully'));
            return $this->redirect(route('v1.panel.admins.index'), navigate: true);
        }

        session()->flash('error', __('panel.error_updating_admin'));
    }

    public function cancel()
    {
        return $this->redirect(route('v1.panel.admins.index'), navigate: true);
    }
}
