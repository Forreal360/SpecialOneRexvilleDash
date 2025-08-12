<?php

namespace App\Livewire\V1\Panel\Client\Vehicle;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\ClientVehicle;
use App\Models\VehicleMake;
use App\Models\VehicleModel;
use Carbon\Carbon;

class GetVehiclesComponent extends Component
{
    use WithPagination;

    public $search = '';
    public $perPage = 10;
    public $status = '';
    public $sortBy = 'status';
    public $clientId = '';
    public $sortDirection = 'asc';
    public $model_id = '';
    public $make_id = '';
    public $year = '';
    public $buy_date = '';
    public $vin = '';

    protected $queryString = [
        'search' => ['except' => ''],
        'perPage' => ['except' => 10],
        'status' => ['except' => ''],
        'sortBy' => ['except' => 'status'],
        'sortDirection' => ['except' => 'asc'],
        'clientId' => ['except' => ''],
        'model_id' => ['except' => ''],
        'make_id' => ['except' => ''],
        'year' => ['except' => ''],
        'buy_date' => ['except' => ''],
        'vin' => ['except' => ''],
    ];

    protected function rules()
    {
        return [
            'perPage' => 'in:5,10,20,50,100',
            'search' => 'nullable|string|max:255',
            'status' => 'nullable|in:A,I',
            'clientId' => 'nullable|exists:clients,id',
            'model_id' => 'nullable|exists:vehicle_models,id',
            'make_id' => 'nullable|exists:vehicle_makes,id',
            'year' => 'nullable|integer|min:1900|max:' . (date('Y') + 1),
            'buy_date' => 'nullable|date',
            'vin' => 'nullable|string|max:255',
        ];
    }

    public function mount($clientId)
    {
        $this->clientId = $clientId;
    }

    public function resetFilters()
    {
        $this->reset(['search', 'perPage', 'status', 'sortBy', 'sortDirection', 'model_id', 'make_id', 'year', 'buy_date', 'vin']);
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatus()
    {
        $this->resetPage();
    }

    public function updatingModelId()
    {
        $this->resetPage();
    }

    public function updatingMakeId()
    {
        $this->resetPage();
        // Limpiar el modelo cuando cambia la marca
        $this->model_id = '';
    }

    public function updatingYear()
    {
        $this->resetPage();
    }

    public function updatingBuyDate()
    {
        $this->resetPage();
    }

    public function updatingVin()
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

    public function getFilteredModels()
    {
        if ($this->make_id) {
            return VehicleModel::where('make_id', $this->make_id)
                ->orderBy('name')
                ->get();
        }

        return collect(); // Retorna colección vacía si no hay marca seleccionada
    }

    public function render()
    {
        $vehicles = ClientVehicle::where('client_id', $this->clientId)
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('vin', 'like', '%' . $this->search . '%')
                      ->orWhere('year', 'like', '%' . $this->search . '%')
                      ->orWhereHas('make', function ($makeQuery) {
                          $makeQuery->where('name', 'like', '%' . $this->search . '%');
                      })
                      ->orWhereHas('model', function ($modelQuery) {
                          $modelQuery->where('name', 'like', '%' . $this->search . '%');
                      });
                });
            })
            ->when($this->model_id, function ($query) {
                $query->where('model_id', $this->model_id);
            })
            ->when($this->make_id, function ($query) {
                $query->where('make_id', $this->make_id);
            })
            ->when($this->year, function ($query) {
                $query->where('year', $this->year);
            })
            ->when($this->buy_date, function ($query) {
                $buy_date = Carbon::parse($this->buy_date)->format('Y-m-d');
                $query->where('buy_date', $buy_date);
            })
            ->when($this->vin, function ($query) {
                $query->where('vin', 'like', '%' . $this->vin . '%');
            })
            ->when($this->status, function ($query) {
                $query->where('status', $this->status);
            })
            ->orderBy($this->sortBy, $this->sortDirection)
            ->paginate($this->perPage);

        // Obtener marcas y modelos para los filtros
        $makes = VehicleMake::orderBy('name')->get();
        $models = $this->getFilteredModels();

        $statusOptions = [
            'active' => 'Activo',
            'inactive' => 'Inactivo',
        ];

        $perPageOptions = [5, 10, 20, 50, 100];

        return view('v1.panel.client.vehicle.get-vehicles-component', compact('vehicles', 'statusOptions', 'perPageOptions', 'makes', 'models'));
    }

    public function updateStatus($id, $status)
    {
        ClientVehicle::find($id)->update(['status' => $status]);
    }


}

