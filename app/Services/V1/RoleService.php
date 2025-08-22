<?php

declare(strict_types=1);

namespace App\Services\V1;

use \App\Models\Role;
use App\Services\V1\Service;

class RoleService extends Service
{
    /**
     * Constructor - Set the model class
     */
    public function __construct()
    {
        $this->modelClass = Role::class;

        // Configure searchable fields for this service
        $this->searchableFields = [
            'name',
            'alias'
        ];

        // Configure pagination
        $this->per_page = 10;
    }

    /**
     * Get roles with permissions loaded
     *
     * @param array $filters
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getPaginatedWithPermissions(array $filters = []): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        $query = $this->modelClass::with('permissions');

        if (isset($filters['search'])) {
            $query->where(function ($q) use ($filters) {
                foreach ($this->searchableFields as $field) {
                    $q->orWhere($field, 'like', '%' . $filters['search'] . '%');
                }
            });
        }

        return $query->paginate($this->per_page);
    }

    /**
     * Get all permissions available
     *
     * @return \Illuminate\Support\Collection
     */
    public function getAllPermissions(): \Illuminate\Support\Collection
    {
        return \Spatie\Permission\Models\Permission::where('guard_name', 'panel')->get();
    }

    /**
     * Get permissions grouped by module
     *
     * @return \Illuminate\Support\Collection
     */
    public function getPermissionsByModule(): \Illuminate\Support\Collection
    {
        return \Spatie\Permission\Models\Permission::where('guard_name', 'panel')
            ->get()
            ->groupBy('module');
    }
}
