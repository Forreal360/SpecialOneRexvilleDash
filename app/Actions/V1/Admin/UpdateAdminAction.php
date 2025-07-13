<?php

declare(strict_types=1);

namespace App\Actions\V1\Admin;

use App\Actions\V1\Action;
use App\Support\ActionResult;
use Illuminate\Support\Facades\DB;
use App\Services\V1\AdminService;

class UpdateAdminAction extends Action
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
            "id" => "required|integer|exists:admins,id",
            "name" => "required|string|max:255",
            "last_name" => "required|string|max:255",
            "email" => "required|email|unique:admins,email," . $data['id'],
            "password" => "nullable|string|min:8|confirmed",
            "status" => "nullable|string|in:A,I",
        ]);

        // Business logic with transaction
        return DB::transaction(function () use ($validated) {
            $adminId = $validated['id'];

            // Remove id from data to update
            unset($validated['id']);

            // Remove password if not provided
            if (empty($validated['password'])) {
                unset($validated['password']);
            }

            $this->adminService->update((int) $adminId, $validated);

            return $this->successResult();

        });
    }
}
