<?php

declare(strict_types=1);

namespace App\Livewire\V1\Panel\Client\Service;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\ClientService;
use App\Models\ClientVehicle;
use App\Models\VehicleService;

class GetClientServicesComponent extends Component
{
    use WithPagination;

    public $search = '';
    public $perPage = 10;
    public $sortBy = 'date';
    public $clientId = '';
    public $sortDirection = 'desc';
    public $vehicle_id = '';
    public $service_id = '';
    public $date_from = '';
    public $date_to = '';

    protected $queryString = [
        'search' => ['except' => ''],
        'perPage' => ['except' => 10],
        'sortBy' => ['except' => 'date'],
        'sortDirection' => ['except' => 'desc'],
        'clientId' => ['except' => ''],
        'vehicle_id' => ['except' => ''],
        'service_id' => ['except' => ''],
        'date_from' => ['except' => ''],
        'date_to' => ['except' => ''],
    ];

    protected function rules()
    {
        return [
            'perPage' => 'in:5,10,20,50,100',
            'search' => 'nullable|string|max:255',
            'clientId' => 'nullable|exists:clients,id',
            'vehicle_id' => 'nullable|exists:client_vehicles,id',
            'service_id' => 'nullable|exists:vehicle_services,id',
            'date_from' => 'nullable|date',
            'date_to' => 'nullable|date',
        ];
    }

    public function mount($clientId)
    {
        $this->clientId = $clientId;
        
        // Auto-select vehicle if passed as query parameter
        if (request()->has('vehicle_id')) {
            $this->vehicle_id = request()->query('vehicle_id');
        }
    }

    public function resetFilters()
    {
        $this->reset(['search', 'perPage', 'sortBy', 'sortDirection', 'vehicle_id', 'service_id', 'date_from', 'date_to']);
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingVehicleId()
    {
        $this->resetPage();
    }

    public function updatingServiceId()
    {
        $this->resetPage();
    }

    public function updatingDateFrom()
    {
        $this->resetPage();
    }

    public function updatingDateTo()
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

    public function render()
    {
        $clientServices = ClientService::where('client_id', $this->clientId)
            ->with(['vehicle.make', 'vehicle.model', 'service', 'client'])
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->whereHas('service', function ($serviceQuery) {
                        $serviceQuery->where('name', 'like', '%' . $this->search . '%');
                    })
                    ->orWhereDate('date', $this->search);
                });
            })
            ->when($this->vehicle_id, function ($query) {
                $query->where('vehicle_id', $this->vehicle_id);
            })
            ->when($this->service_id, function ($query) {
                $query->where('service_id', $this->service_id);
            })
            
            ->orderBy($this->sortBy, $this->sortDirection)
            ->paginate($this->perPage);

        // Get filter options
        $vehicles = ClientVehicle::where('client_id', $this->clientId)
            ->where('status', 'A')
            ->with(['make', 'model'])
            ->orderBy('year', 'desc')
            ->get();

        $services = VehicleService::where('status', 'A')
            ->orderBy('name')
            ->get();

        $perPageOptions = [5, 10, 20, 50, 100];

        return view('v1.panel.client.service.get-client-services-component', 
            compact('clientServices', 'vehicles', 'services', 'perPageOptions'));
    }
} 