<?php

declare(strict_types=1);

namespace App\Actions\V1\Ticket;

use App\Actions\V1\Action;
use App\Support\ActionResult;
use App\Services\V1\TicketService;
use Illuminate\Support\Facades\DB;

class SendMessageAction extends Action
{
    public function __construct(
        private TicketService $ticketService
    ) {
        // TicketService injected
    }

    /**
     * Handle the action logic
     *
     * @param array|object $data
     * @return ActionResult
     */
    public function handle($data): ActionResult
    {

        $this->validatePermissions([
            "tickets.respond"
        ]);


        $validated = $this->validateData($data, [
            "ticket_id" => "required|exists:tickets,id",
            "message" => "required|string|max:1000",
            "from_id" => "required|integer",
        ]);

        return DB::transaction(function () use ($validated) {
            $message = $this->ticketService->addMessage(
                (int) $validated['ticket_id'],
                (int) $validated['from_id'],
                "admin",
                $validated['message']
            );

            if (!$message) {
                return $this->errorResult('No se pudo enviar el mensaje. El ticket puede estar cerrado.');
            }

            return $this->successResult($message, 'Mensaje enviado exitosamente');
        });
    }
}
