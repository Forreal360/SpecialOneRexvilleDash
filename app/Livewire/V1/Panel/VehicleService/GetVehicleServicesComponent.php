<?php

declare(strict_types=1);

namespace App\Livewire\V1\Panel\VehicleService;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\VehicleService;

class GetVehicleServicesComponent extends Component
{
    use WithPagination;

    public $search = '';
    public $perPage = 10;
    public $status = '';
    public $sortBy = 'name';
    public $sortDirection = 'asc';

    protected $queryString = [
        'search' => ['except' => ''],
        'perPage' => ['except' => 10],
        'status' => ['except' => ''],
        'sortBy' => ['except' => 'name'],
        'sortDirection' => ['except' => 'asc'],
    ];

    protected function rules()
    {
        return [
            'perPage' => 'in:5,10,20,50,100',
            'search' => 'nullable|string|max:255',
            'status' => 'nullable|in:A,I',
        ];
    }

    public function resetFilters()
    {
        $this->reset(['search', 'perPage', 'status', 'sortBy', 'sortDirection']);
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatus()
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
        $vehicleServices = VehicleService::query()
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%');
            })
            ->when($this->status, function ($query) {
                $query->where('status', $this->status);
            })
            ->orderBy($this->sortBy, $this->sortDirection)
            ->paginate($this->perPage);

        $statusOptions = [
            'A' => 'Activo',
            'I' => 'Inactivo',
        ];

        $perPageOptions = [5, 10, 20, 50, 100];

        return view('v1.panel.vehicle-service.get-vehicle-services-component', 
            compact('vehicleServices', 'statusOptions', 'perPageOptions'));
    }

    public function updateStatus($id, $status)
    {
        VehicleService::find($id)->update(['status' => $status]);
        session()->flash('success', __('panel.vehicle_service_status_updated'));
    }


} 