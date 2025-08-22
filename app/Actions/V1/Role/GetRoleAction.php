<?php

declare(strict_types=1);

namespace App\Actions\V1\Role;

use App\Actions\V1\Action;
use App\Support\ActionResult;
use App\Services\V1\RoleService;

class GetRoleAction extends Action
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
            'id' => 'required|exists:roles,id'
        ], [
            'id.required' => 'El ID del rol es obligatorio',
            'id.exists' => 'El rol no existe'
        ]);

        $role = $this->roleService->findById($validated['id']);
        $role->load('permissions');

        return $this->successResult(
            data: $role,
            message: 'Rol obtenido exitosamente'
        );
    }
}
