<?php

namespace App\Livewire\V1\Panel\Admin;

use App\Models\Admin;
use Livewire\Component;
use Livewire\WithPagination;
use Flux\Flux;

class GetAdminsComponent extends \Livewire\Component
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

    protected $rules = [
        'perPage' => 'in:5,10,20,50,100',
        'search' => 'nullable|string|max:255',
        'status' => 'nullable|in:active,inactive',
    ];

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
        $admins = Admin::query()
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('email', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->status, function ($query) {
                $query->where('status', $this->status);
            })
            ->orderBy($this->sortBy, $this->sortDirection)
            ->paginate($this->perPage);

        $statusOptions = [
            'active' => 'Activo',
            'inactive' => 'Inactivo',
        ];

        $perPageOptions = [5, 10, 20, 50, 100];

        return view('v1.panel.admin.get-admins-component', compact('admins', 'statusOptions', 'perPageOptions'));
    }

    public function updateStatus($id, $status)
    {
        Admin::find($id)->update(['status' => $status]);
    }


}
