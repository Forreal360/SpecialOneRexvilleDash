<?php

namespace App\Livewire\V1\Panel\Client;

use App\Models\Client;
use Livewire\Component;
use Livewire\WithPagination;
use Flux\Flux;

class GetClientsComponent extends \Livewire\Component
{
    use WithPagination;

    public $search = '';
    public $perPage = 10;
    public $status = '';
    public $name = '';
    public $email = '';
    public $phone = '';
    public $license_number = '';
    public $sortBy = 'name';
    public $sortDirection = 'asc';

    protected $queryString = [
        'search' => ['except' => ''],
        'perPage' => ['except' => 10],
        'status' => ['except' => ''],
        'name' => ['except' => ''],
        'email' => ['except' => ''],
        'phone' => ['except' => ''],
        'license_number' => ['except' => ''],
        'sortBy' => ['except' => 'name'],
        'sortDirection' => ['except' => 'asc'],
    ];

    protected $rules = [
        'perPage' => 'in:5,10,20,50,100',
        'search' => 'nullable|string|max:255',
        'status' => 'nullable|in:A,I',
        'name' => 'nullable|string|max:255',
        'email' => 'nullable|string|max:255',
        'phone' => 'nullable|string|max:30',
        'license_number' => 'nullable|string|max:50',
    ];

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
        $clients = Client::query()
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->whereRaw("CONCAT(name, ' ', last_name) LIKE ?", ['%' . $this->search . '%'])
                      ->orWhere('email', 'like', '%' . $this->search . '%')
                      ->orWhere('phone', 'like', '%' . $this->search . '%')
                      ->orWhere('license_number', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->name, function ($query) {
                $query->whereRaw("CONCAT(name, ' ', last_name) LIKE ?", ['%' . $this->name . '%']);
            })
            ->when($this->email, function ($query) {
                $query->where('email', 'like', '%' . $this->email . '%');
            })
            ->when($this->status, function ($query) {
                $query->where('status', $this->status);
            })
            ->when($this->phone, function ($query) {
                $query->where(function ($q) {
                    $q->where('phone_code', 'like', '%' . $this->phone . '%')
                      ->orWhere('phone', 'like', '%' . $this->phone . '%')
                      ->orWhereRaw("CONCAT(phone_code, ' ', phone) LIKE ?", ['%' . $this->phone . '%']);
                });
            })
            ->when($this->license_number, function ($query) {
                $query->where('license_number', 'like', '%' . $this->license_number . '%');
            })
            ->orderBy($this->sortBy, $this->sortDirection)
            ->paginate($this->perPage);

        $statusOptions = [
            'active' => 'Activo',
            'inactive' => 'Inactivo',
        ];

        $perPageOptions = [5, 10, 20, 50, 100];

        return view('v1.panel.client.get-clients-component', compact('clients', 'statusOptions', 'perPageOptions'));
    }

    public function updateStatus($id, $status)
    {
        Client::find($id)->update(['status' => $status]);
    }


}
