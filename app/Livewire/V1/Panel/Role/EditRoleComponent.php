<?php

namespace App\Livewire\V1\Panel\Role;

use Livewire\Component;
use App\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class EditRoleComponent extends Component
{
    public $roleId;
    public $name = '';
    public $alias = '';
    public $selectedPermissions = [];
    public $permissionsByModule = [];
    public $role;

    protected function rules()
    {
        return [
            'alias' => ['required', 'string', 'max:255', Rule::unique('roles')->ignore($this->roleId)],
            'selectedPermissions' => 'nullable|array',
            'selectedPermissions.*' => 'exists:permissions,id'
        ];
    }

    protected $messages = [
        'alias.required' => 'El alias del rol es obligatorio',
        'alias.unique' => 'Ya existe un rol con este alias',
        'selectedPermissions.*.exists' => 'Uno o más permisos seleccionados no son válidos'
    ];

    public function mount($id)
    {
        $this->roleId = $id;
        $this->loadRole();
        $this->loadPermissions();
    }

    public function loadRole()
    {
        $this->role = Role::with('permissions')->findOrFail($this->roleId);
        $this->alias = $this->role->alias;
        $this->selectedPermissions = $this->role->permissions->pluck('id')->toArray();
    }

    public function loadPermissions()
    {
        $permissions = Permission::where('guard_name', 'admin')->get();
        
        $this->permissionsByModule = [];
        foreach ($permissions as $permission) {
            $module = $permission->module ?? 'general';
            if (!isset($this->permissionsByModule[$module])) {
                $this->permissionsByModule[$module] = collect();
            }
            $this->permissionsByModule[$module]->push($permission);
        }
    }

    public function updateRole()
    {
        $this->validate();

        // Generar el name automáticamente desde el alias
        $role_name = strtolower(str_replace(' ', '-', $this->alias));

        $this->role->update([
            'name' => $role_name,
            'alias' => $this->alias
        ]);

        $permissions = Permission::whereIn('id', $this->selectedPermissions ?? [])->pluck('name');
        $this->role->syncPermissions($permissions);

        session()->flash('success', 'Rol actualizado exitosamente');
        return redirect()->route('v1.panel.roles.index');
    }

    public function togglePermission($permissionId)
    {
        if (in_array($permissionId, $this->selectedPermissions)) {
            $this->selectedPermissions = array_values(array_diff($this->selectedPermissions, [$permissionId]));
        } else {
            $this->selectedPermissions[] = $permissionId;
        }
    }

    public function selectAllModulePermissions($module)
    {
        if (isset($this->permissionsByModule[$module])) {
            foreach ($this->permissionsByModule[$module] as $permission) {
                if (!in_array($permission->id, $this->selectedPermissions)) {
                    $this->selectedPermissions[] = $permission->id;
                }
            }
        }
    }

    public function deselectAllModulePermissions($module)
    {
        if (isset($this->permissionsByModule[$module])) {
            foreach ($this->permissionsByModule[$module] as $permission) {
                $this->selectedPermissions = array_values(array_diff($this->selectedPermissions, [$permission->id]));
            }
        }
    }

    public function selectAllPermissions()
    {
        $allPermissionIds = [];
        foreach ($this->permissionsByModule as $permissions) {
            foreach ($permissions as $permission) {
                $allPermissionIds[] = $permission->id;
            }
        }
        $this->selectedPermissions = array_unique($allPermissionIds);
    }

    public function deselectAllPermissions()
    {
        $this->selectedPermissions = [];
    }

    public function render()
    {
        return view('v1.panel.role.edit-role-component');
    }
}
