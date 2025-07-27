<?php

namespace App\Livewire\V1\Panel\Ticket;

use App\Models\Ticket;
use App\Actions\V1\Ticket\SendMessageAction;
use App\Actions\V1\Ticket\CloseTicketAction;
use Livewire\Component;
use App\Livewire\Concerns\HandlesActionResults;
use Illuminate\Support\Facades\Auth;

class ViewTicketComponent extends Component
{
    use HandlesActionResults;

    public $ticketId;
    public $ticket;
    public $newMessage = '';

    protected $rules = [
        'newMessage' => 'required|string|max:1000',
    ];

    public function mount($ticketId)
    {
        $this->ticketId = $ticketId;
        $this->loadTicket();
    }

    public function loadTicket()
    {
        $this->ticket = Ticket::with(['client', 'messages.fromeable'])
            ->findOrFail($this->ticketId);
    }

    public function sendMessage()
    {
        $this->validate();

        $admin = Auth::guard('admin')->user();

        $result = app(SendMessageAction::class)->handle([
            'ticket_id' => $this->ticketId,
            'message' => $this->newMessage,
            'from_id' => $admin->id,
        ]);

        if ($result->isSuccess()) {
            $this->newMessage = '';
            $this->loadTicket(); // Recargar para mostrar el nuevo mensaje
            $this->dispatch('message-sent');
        }

        $this->handleActionResult($result);
    }

    public function closeTicket()
    {
        $result = app(CloseTicketAction::class)->handle([
            'id' => $this->ticketId
        ]);

        if ($result->isSuccess()) {
            $this->loadTicket(); // Recargar para mostrar el estado actualizado
        }

        $this->handleActionResult($result);
    }

    public function render()
    {
        return view('v1.panel.ticket.view-ticket-component');
    }
}
