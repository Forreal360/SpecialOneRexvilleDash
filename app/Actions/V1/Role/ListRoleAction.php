<?php

declare(strict_types=1);

namespace App\Actions\V1\Role;

use App\Actions\V1\Action;
use App\Support\ActionResult;
use App\Services\V1\RoleService;

class ListRoleAction extends Action
{
    public function __construct(
        private RoleService $roleService
    ) {}

    public function handle($data): ActionResult
    {
        $this->validatePermissions([
            'roles.get'
        ]);

        $validated = $this->validateData($data, [
            'search' => 'nullable|string|max:255',
            'page' => 'nullable|integer|min:1',
        ]);

        $roles = $this->roleService->getPaginatedWithPermissions($validated);

        return $this->successResult(
            data: $roles,
            message: 'Roles obtenidos exitosamente'
        );
    }
}
