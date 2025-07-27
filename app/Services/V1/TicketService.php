<?php

declare(strict_types=1);

namespace App\Services\V1;

use App\Models\Ticket;
use App\Models\TicketMessage;
use App\Services\V1\Service;

class TicketService extends Service
{
    /**
     * Constructor - Set the model class
     */
    public function __construct()
    {
        $this->modelClass = Ticket::class;

        // Configure searchable fields for this service
        $this->searchableFields = [
            'subject',
        ];

        // Configure pagination
        $this->per_page = 10;
    }

    /**
     * Crear un nuevo ticket
     *
     * @param array $data
     * @return Ticket
     */
    public function createTicket(array $data): Ticket
    {
        return Ticket::create([
            'subject' => $data['subject'],
            'client_id' => $data['client_id'],
            'status' => 'open',
        ]);
    }

    /**
     * Cerrar un ticket
     *
     * @param int $ticketId
     * @return bool
     */
    public function closeTicket(int $ticketId): bool
    {
        $ticket = Ticket::find($ticketId);

        if (!$ticket) {
            return false;
        }

        return $ticket->update(['status' => 'closed']);
    }

    /**
     * Cambiar el estado de un ticket
     *
     * @param int $ticketId
     * @param string $status
     * @return bool
     */
    public function updateTicketStatus(int $ticketId, string $status): bool
    {
        $ticket = Ticket::find($ticketId);

        if (!$ticket) {
            return false;
        }

        return $ticket->update(['status' => $status]);
    }

    /**
     * Agregar un mensaje a un ticket
     *
     * @param int $ticketId
     * @param int $fromId
     * @param string $fromType
     * @param string $message
     * @return TicketMessage|null
     */
    public function addMessage(int $ticketId, int $fromId, string $fromType, string $message): ?TicketMessage
    {
        $ticket = Ticket::find($ticketId);
        $ticket->new_message_from_support = "Y";
        $ticket->save();

        if (!$ticket || $ticket->isClosed()) {
            return null;
        }

        return TicketMessage::create([
            'ticket_id' => $ticketId,
            'fromeable_type' => $fromType,
            'fromeable_id' => $fromId,
            'message' => $message,
            'message_type' => 'text',
        ]);
    }

    /**
     * Obtener tickets con filtros
     *
     * @param array $filters
     * @param int $perPage
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function getTickets(array $filters = [], int $perPage = 10)
    {
        $query = Ticket::with(['client', 'lastMessage.fromeable']);

        // Filtrar por estado
        if (isset($filters['status']) && !empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        // Filtrar por cliente
        if (isset($filters['client_id']) && !empty($filters['client_id'])) {
            $query->where('client_id', $filters['client_id']);
        }

        // Búsqueda por asunto
        if (isset($filters['search']) && !empty($filters['search'])) {
            $query->where('subject', 'like', '%' . $filters['search'] . '%');
        }

        // Ordenar por más reciente
        $query->orderBy('updated_at', 'desc');

        return $query->paginate($perPage);
    }

    /**
     * Obtener un ticket con sus mensajes
     *
     * @param int $ticketId
     * @return Ticket|null
     */
    public function getTicketWithMessages(int $ticketId): ?Ticket
    {
        return Ticket::with(['client', 'messages.fromeable'])
            ->find($ticketId);
    }
}
