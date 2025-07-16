<?php

namespace App\Livewire\V1\Panel\Promotion;

use Livewire\Component;
use App\Models\Promotion;
use Livewire\WithPagination;

class GetPromotionsComponent extends Component
{
    use WithPagination;

    public $search = '';
    public $perPage = 10;
    public $status = '';
    public $sortBy = 'title';
    public $sortDirection = 'asc';
    public $date_type = 'created_at';
    public $filter_start_date = null;
    public $filter_end_date = null;

    protected $queryString = [
        'search' => ['except' => ''],
        'perPage' => ['except' => 10],
        'status' => ['except' => ''],
        'sortBy' => ['except' => 'title'],
        'sortDirection' => ['except' => 'asc'],
        'date_type' => ['except' => 'created_at'],
        'filter_start_date' => ['except' => null],
        'filter_end_date' => ['except' => null],
    ];

    protected $rules = [
        'perPage' => 'in:5,10,20,50,100',
        'search' => 'nullable|string|max:255',
        'status' => 'nullable|in:A,I',
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

    public function updatingDateType()
    {
        $this->resetPage();
    }

    public function updatingFilterStartDate()
    {
        $this->resetPage();
    }

    public function updatingFilterEndDate()
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
        $promotions = Promotion::query()
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('title', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->status, function ($query) {
                $query->where('status', $this->status);
            })
            ->when($this->filter_start_date, function ($query) {
                $query->where($this->date_type, '>=', $this->filter_start_date);
            })
            ->when($this->filter_end_date, function ($query) {
                $query->where($this->date_type, '<=', $this->filter_end_date);
            })
            ->orderBy($this->sortBy, $this->sortDirection)
            ->paginate($this->perPage);

        $statusOptions = [
            'A' => 'Activo',
            'I' => 'Inactivo',
        ];

        $perPageOptions = [5, 10, 20, 50, 100];

        return view('v1.panel.promotion.get-promotions-component', compact('promotions', 'statusOptions', 'perPageOptions'));
    }

    public function updateStatus($id, $status)
    {
        Promotion::find($id)?->update(['status' => $status]);
    }
}
