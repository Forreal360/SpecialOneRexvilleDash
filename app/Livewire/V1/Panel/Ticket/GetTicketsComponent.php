<?php

namespace App\Livewire\V1\Panel\Ticket;

use App\Models\Ticket;
use App\Models\Client;
use App\Actions\V1\Ticket\CloseTicketAction;
use Livewire\Component;
use Livewire\WithPagination;
use App\Livewire\Concerns\HandlesActionResults;

class GetTicketsComponent extends Component
{
    use WithPagination, HandlesActionResults;

    public $search = '';
    public $perPage = 10;
    public $status = '';
    public $client_id = '';
    public $sortBy = 'updated_at';
    public $sortDirection = 'desc';

    protected $queryString = [
        'search' => ['except' => ''],
        'perPage' => ['except' => 10],
        'status' => ['except' => ''],
        'client_id' => ['except' => ''],
        'sortBy' => ['except' => 'updated_at'],
        'sortDirection' => ['except' => 'desc'],
    ];

    protected $rules = [
        'perPage' => 'in:5,10,20,50,100',
        'search' => 'nullable|string|max:255',
        'status' => 'nullable|in:open,in_progress,closed',
        'client_id' => 'nullable|exists:clients,id',
    ];

    public function resetFilters()
    {
        $this->reset(['search', 'status', 'client_id']);
        $this->resetPage();
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatus()
    {
        $this->resetPage();
    }

    public function updatingClientId()
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

    public function closeTicket($ticketId)
    {
        $result = app(CloseTicketAction::class)->handle([
            'id' => $ticketId
        ]);

        $this->handleActionResult($result);
    }

    public function render()
    {
        $tickets = Ticket::query()
            ->with(['client', 'lastMessage.fromeable'])
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('subject', 'like', '%' . $this->search . '%')
                      ->orWhereHas('client', function ($q) {
                          $q->whereRaw("CONCAT(name, ' ', last_name) LIKE ?", ['%' . $this->search . '%']);
                      });
                });
            })
            ->when($this->status, function ($query) {
                $query->where('status', $this->status);
            })
            ->when($this->client_id, function ($query) {
                $query->where('client_id', $this->client_id);
            })
            ->orderBy($this->sortBy, $this->sortDirection)
            ->paginate($this->perPage);

        $clients = Client::orderBy('name')->get();

        $statusOptions = [
            'open' => __('panel.open'),
            'in_progress' => __('panel.in_progress'),
            'closed' => __('panel.closed'),
        ];

        $perPageOptions = [5, 10, 20, 50, 100];

        return view('v1.panel.ticket.get-tickets-component', compact('tickets', 'clients', 'statusOptions', 'perPageOptions'));
    }
}
