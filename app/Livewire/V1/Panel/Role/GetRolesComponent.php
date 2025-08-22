<?php

namespace App\Livewire\V1\Panel\Role;

use Livewire\Component;
use App\Models\Role;
use App\Models\Admin;
use Livewire\WithPagination;

class GetRolesComponent extends Component
{
    use WithPagination;

    public $search = '';
    public $perPage = 10;
    public $sortBy = 'name';
    public $sortDirection = 'asc';

    protected $queryString = [
        'search' => ['except' => ''],
        'perPage' => ['except' => 10],
        'sortBy' => ['except' => 'name'],
        'sortDirection' => ['except' => 'asc'],
    ];

    protected $rules = [
        'perPage' => 'in:5,10,25,50,100',
        'search' => 'nullable|string|max:255',
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingPerPage()
    {
        $this->resetPage();
    }

    public function sort($field)
    {
        if ($this->sortBy === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function canDeleteRole($roleId)
    {
        // Verificar si hay admins que tienen este rol asignado
        return !Admin::whereHas('roles', function ($query) use ($roleId) {
            $query->where('roles.id', $roleId);
        })->exists();
    }

    public function deleteRole($roleId)
    {
        $role = Role::findOrFail($roleId);
        
        // Verificar si el rol se puede eliminar
        if (!$this->canDeleteRole($roleId)) {
            session()->flash('error', __('roles.cannot_delete_role_in_use'));
            return;
        }

        // Eliminar el rol
        $role->delete();
        
        session()->flash('success', __('roles.role_deleted_successfully'));
        
        // Refrescar la lista
        $this->resetPage();
    }

    public function render()
    {
        $roles = Role::query()
            ->with('permissions')
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('alias', 'like', '%' . $this->search . '%');
                });
            })
            ->orderBy($this->sortBy, $this->sortDirection)
            ->paginate($this->perPage);

        $perPageOptions = [5, 10, 25, 50, 100];

        return view('v1.panel.role.get-roles-component', compact('roles', 'perPageOptions'));
    }
}
