<?php

declare(strict_types=1);

namespace App\Services\V1;

use App\Models\VehicleService;
use App\Services\V1\Service;

class VehicleServiceService extends Service
{
    public function __construct()
    {
        $this->modelClass = VehicleService::class;

        $this->searchableFields = [
            'name',
        ];

        $this->per_page = 10;
    }

    public function getActiveServices(): \Illuminate\Support\Collection
    {
        return $this->modelClass::active()->orderBy('name')->get();
    }

    public function searchByName(string $term): \Illuminate\Support\Collection
    {
        return $this->modelClass::where('name', 'like', '%' . $term . '%')
            ->orderBy('name')
            ->get();
    }

    public function getStatistics(): array
    {
        return [
            'total' => $this->count(),
            'active' => $this->count(['status' => 'A']),
            'inactive' => $this->count(['status' => 'I']),
        ];
    }
}