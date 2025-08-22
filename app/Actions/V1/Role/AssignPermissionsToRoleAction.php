<?php

declare(strict_types=1);

namespace App\Actions\V1\Role;

use App\Actions\V1\Action;
use App\Support\ActionResult;
use App\Services\V1\RoleService;
use Illuminate\Support\Facades\DB;

class AssignPermissionsToRoleAction extends Action
{
    public function __construct(
        private RoleService $roleService
    ) {}

    public function handle($data): ActionResult
    {
        $this->validatePermissions([
            'roles.assign-permissions'
        ]);

        $validated = $this->validateData($data, [
            'role_id' => 'required|exists:roles,id',
            'permissions' => 'required|array',
            'permissions.*' => 'exists:permissions,id'
        ], [
            'role_id.required' => 'El ID del rol es obligatorio',
            'role_id.exists' => 'El rol no existe',
            'permissions.required' => 'Debe seleccionar al menos un permiso',
            'permissions.*.exists' => 'Uno o más permisos seleccionados no son válidos'
        ]);

        return DB::transaction(function () use ($validated) {
            $role = $this->roleService->findById($validated['role_id']);
            
            $role->syncPermissions($validated['permissions']);

            return $this->successResult(
                data: $role->load('permissions'),
                message: 'Permisos asignados exitosamente al rol'
            );
        });
    }
}
