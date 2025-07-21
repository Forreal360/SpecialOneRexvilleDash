<?php

declare(strict_types=1);

namespace App\Services\V1;

use App\Models\VehicleService;
use App\Services\V1\Service;

class VehicleServiceService extends Service
{
    /**
     * Constructor - Set the model class
     */
    public function __construct()
    {
        $this->modelClass = VehicleService::class;

        // Configure searchable fields for this service
        $this->searchableFields = [
            'name',
        ];

        // Configure pagination
        $this->per_page = 10;
    }

    /**
     * Get active vehicle services
     *
     * @return \Illuminate\Support\Collection
     */
    public function getActive(): \Illuminate\Support\Collection
    {
        return $this->modelClass::where('status', 'A')
            ->orderBy('name')
            ->get();
    }

    /**
     * Search by name with custom logic
     *
     * @param string $term
     * @return \Illuminate\Support\Collection
     */
    public function searchByName(string $term): \Illuminate\Support\Collection
    {
        return $this->modelClass::where('name', 'like', '%' . $term . '%')
            ->orderBy('name')
            ->get();
    }

    /**
     * Get paginated with search functionality
     *
     * @param array $filters
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getPaginatedWithFilters(array $filters = []): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        $query = $this->modelClass::query();

        // Apply search filter
        if (!empty($filters['search'])) {
            $query->where('name', 'like', '%' . $filters['search'] . '%');
        }

        // Apply status filter
        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        // Apply sorting
        $sortBy = $filters['sortBy'] ?? 'name';
        $sortDirection = $filters['sortDirection'] ?? 'asc';
        $query->orderBy($sortBy, $sortDirection);

        $perPage = $filters['perPage'] ?? $this->per_page;
        return $query->paginate($perPage);
    }

    /**
     * Get statistics or aggregated data
     *
     * @return array
     */
    public function getStatistics(): array
    {
        return [
            'total' => $this->count(),
            'active' => $this->count(['status' => 'A']),
            'inactive' => $this->count(['status' => 'I']),
        ];
    }
} 