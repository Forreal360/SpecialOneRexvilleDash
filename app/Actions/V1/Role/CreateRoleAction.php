<?php

declare(strict_types=1);

namespace App\Actions\V1\Role;

use App\Actions\V1\Action;
use App\Support\ActionResult;
use App\Services\V1\RoleService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CreateRoleAction extends Action
{
    public function __construct(
        private RoleService $roleService
    ) {}

    public function handle($data): ActionResult
    {
        $this->validatePermissions([
            'roles.create'
        ]);

        $validated = $this->validateData($data, [
            'name' => 'required|string|max:255|unique:roles,name',
            'alias' => 'nullable|string|max:255',
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,id'
        ], [
            'name.required' => 'El nombre del rol es obligatorio',
            'name.unique' => 'Ya existe un rol con este nombre',
            'permissions.*.exists' => 'Uno o más permisos seleccionados no son válidos'
        ]);

        return DB::transaction(function () use ($validated) {
            if (empty($validated['alias'])) {
                $validated['alias'] = Str::slug($validated['name'], '_');
            }

            $validated['guard_name'] = 'admin';
            
            $role = $this->roleService->create($validated);

            if (!empty($validated['permissions'])) {
                $role->syncPermissions($validated['permissions']);
            }

            return $this->successResult(
                data: $role->load('permissions'),
                message: 'Rol creado exitosamente'
            );
        });
    }
}
