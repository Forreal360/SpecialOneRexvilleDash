<?php

declare(strict_types=1);

namespace App\Actions\V1\Admin;

use App\Actions\V1\Action;
use App\Support\ActionResult;
use Illuminate\Support\Facades\DB;
use App\Services\V1\AdminService;

class CreateAdminAction extends Action
{
    /**
     * Constructor - Inject dependencies here
     */
    public function __construct(private AdminService $adminService)
    {

    }

    /**
     * Handle the action logic
     *
     * @param array|object $data
     * @return ActionResult
     */
    public function handle($data): ActionResult
    {

        // Validate input data
        $validated = $this->validateData($data, [
            "name" => "required|string|max:255",
            "last_name" => "required|string|max:255",
            "email" => "required|email|unique:admins,email",
            "password" => "required|string|min:8|confirmed",
            "role" => "nullable|exists:roles,id"
        ]);

        // Business logic with transaction
        return DB::transaction(function () use ($validated) {
            $admin = $this->adminService->create($validated);

            // Asignar rol si se proporcionó
            if (!empty($validated['role'])) {
                $role = \App\Models\Role::find($validated['role']);
                if ($role) {
                    $admin->syncRoles([$role->name]); // Usar nombre del rol, no ID
                }
            }

            return $this->successResult(data: $admin);

        });
    }
}
