<?php

declare(strict_types=1);

namespace App\Services\V1;

use App\Models\ClientService;
use App\Services\V1\Service;

class ClientServiceService extends Service
{
    /**
     * Constructor - Set the model class
     */
    public function __construct()
    {
        $this->modelClass = ClientService::class;

        // Configure searchable fields for this service
        $this->searchableFields = [
            'date',
        ];

        // Configure pagination
        $this->per_page = 10;
    }

    /**
     * Get services for a specific client
     *
     * @param int $clientId
     * @return \Illuminate\Support\Collection
     */
    public function getByClient(int $clientId): \Illuminate\Support\Collection
    {
        return $this->modelClass::where('client_id', $clientId)
            ->with(['vehicle', 'service', 'client'])
            ->orderBy('date', 'desc')
            ->get();
    }

    /**
     * Get services for a specific vehicle
     *
     * @param int $vehicleId
     * @return \Illuminate\Support\Collection
     */
    public function getByVehicle(int $vehicleId): \Illuminate\Support\Collection
    {
        return $this->modelClass::where('vehicle_id', $vehicleId)
            ->with(['vehicle', 'service', 'client'])
            ->orderBy('date', 'desc')
            ->get();
    }

    /**
     * Get paginated with filters and relationships
     *
     * @param array $filters
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getPaginatedWithFilters(array $filters = []): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        $query = $this->modelClass::with(['vehicle', 'service', 'client']);

        // Apply client filter
        if (!empty($filters['client_id'])) {
            $query->where('client_id', $filters['client_id']);
        }

        // Apply vehicle filter
        if (!empty($filters['vehicle_id'])) {
            $query->where('vehicle_id', $filters['vehicle_id']);
        }

        // Apply service filter
        if (!empty($filters['service_id'])) {
            $query->where('service_id', $filters['service_id']);
        }

        // Apply date range filter
        if (!empty($filters['date_from'])) {
            $query->whereDate('date', '>=', $filters['date_from']);
        }

        if (!empty($filters['date_to'])) {
            $query->whereDate('date', '<=', $filters['date_to']);
        }

        // Apply search filter
        if (!empty($filters['search'])) {
            $query->where(function ($q) use ($filters) {
                $q->whereHas('client', function ($clientQuery) use ($filters) {
                    $clientQuery->whereRaw("CONCAT(name, ' ', last_name) LIKE ?", ['%' . $filters['search'] . '%']);
                })
                ->orWhereHas('service', function ($serviceQuery) use ($filters) {
                    $serviceQuery->where('name', 'like', '%' . $filters['search'] . '%');
                })
                ->orWhereDate('date', $filters['search']);
            });
        }

        // Apply sorting
        $sortBy = $filters['sortBy'] ?? 'date';
        $sortDirection = $filters['sortDirection'] ?? 'desc';
        $query->orderBy($sortBy, $sortDirection);

        $perPage = $filters['perPage'] ?? $this->per_page;
        return $query->paginate($perPage);
    }

    /**
     * Get statistics for client services
     *
     * @param int|null $clientId
     * @return array
     */
    public function getStatistics(?int $clientId = null): array
    {
        $query = $this->modelClass::query();
        
        if ($clientId) {
            $query->where('client_id', $clientId);
        }

        return [
            'total' => $query->count(),
            'this_month' => $query->whereMonth('date', now()->month)
                                ->whereYear('date', now()->year)
                                ->count(),
            'this_year' => $query->whereYear('date', now()->year)->count(),
        ];
    }
} 