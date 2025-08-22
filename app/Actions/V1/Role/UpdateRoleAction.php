<?php

declare(strict_types=1);

namespace App\Actions\V1\Role;

use App\Actions\V1\Action;
use App\Support\ActionResult;
use App\Services\V1\RoleService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class UpdateRoleAction extends Action
{
    public function __construct(
        private RoleService $roleService
    ) {}

    public function handle($data): ActionResult
    {
        $this->validatePermissions([
            'roles.update'
        ]);

        $validated = $this->validateData($data, [
            'id' => 'required|exists:roles,id',
            'name' => ['required', 'string', 'max:255', Rule::unique('roles')->ignore($data['id'] ?? null)],
            'alias' => 'nullable|string|max:255',
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,id'
        ], [
            'id.required' => 'El ID del rol es obligatorio',
            'id.exists' => 'El rol no existe',
            'name.required' => 'El nombre del rol es obligatorio',
            'name.unique' => 'Ya existe un rol con este nombre',
            'permissions.*.exists' => 'Uno o más permisos seleccionados no son válidos'
        ]);

        return DB::transaction(function () use ($validated) {
            $role = $this->roleService->findById($validated['id']);

            if (empty($validated['alias'])) {
                $validated['alias'] = Str::slug($validated['name'], '_');
            }

            $role->update([
                'name' => $validated['name'],
                'alias' => $validated['alias']
            ]);

            if (array_key_exists('permissions', $validated)) {
                $role->syncPermissions($validated['permissions'] ?? []);
            }

            return $this->successResult(
                data: $role->load('permissions'),
                message: 'Rol actualizado exitosamente'
            );
        });
    }
}
