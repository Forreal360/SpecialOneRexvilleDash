<?php

namespace App\Livewire\V1\Panel\Client\Vehicle;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\ClientVehicle;

class GetVehiclesComponent extends Component
{
    use WithPagination;

    public $search = '';
    public $perPage = 10;
    public $status = '';
    public $sortBy = 'status';
    public $clientId = '';
    public $sortDirection = 'asc';

    protected $queryString = [
        'search' => ['except' => ''],
        'perPage' => ['except' => 10],
        'status' => ['except' => ''],
        'sortBy' => ['except' => 'status'],
        'sortDirection' => ['except' => 'asc'],
        'clientId' => ['except' => ''],
    ];

    protected $rules = [
        'perPage' => 'in:5,10,20,50,100',
        'search' => 'nullable|string|max:255',
        'status' => 'nullable|in:A,I',
        'clientId' => 'nullable|exists:clients,id',
    ];

    public function mount($clientId)
    {
        $this->clientId = $clientId;
    }

    public function resetFilters()
    {
        $this->reset();
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatus()
    {
        $this->resetPage();
    }

    public function updatingName()
    {
        $this->resetPage();
    }

    public function updatingEmail()
    {
        $this->resetPage();
    }

    public function updatingPhone()
    {
        $this->resetPage();
    }

    public function updatingLicenseNumber()
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
        $vehicles = ClientVehicle::where('client_id', $this->clientId)
            ->orderBy($this->sortBy, $this->sortDirection)
            ->paginate($this->perPage);

        $statusOptions = [
            'active' => 'Activo',
            'inactive' => 'Inactivo',
        ];

        $perPageOptions = [5, 10, 20, 50, 100];

        return view('v1.panel.client.vehicle.get-vehicles-component', compact('vehicles', 'statusOptions', 'perPageOptions'));
    }

    public function updateStatus($id, $status)
    {
        ClientVehicle::find($id)->update(['status' => $status]);
    }


}

