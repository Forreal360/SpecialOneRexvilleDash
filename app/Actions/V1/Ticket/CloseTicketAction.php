<?php

declare(strict_types=1);

namespace App\Actions\V1\Ticket;

use App\Actions\V1\Action;
use App\Support\ActionResult;
use App\Services\V1\TicketService;
use Illuminate\Support\Facades\DB;

class CloseTicketAction extends Action
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
        $validated = $this->validateData($data, [
            "id" => "required|exists:tickets,id",
        ]);

        return DB::transaction(function () use ($validated) {
            $result = $this->ticketService->closeTicket((int) $validated['id']);

            if (!$result) {
                return $this->errorResult('No se pudo cerrar el ticket');
            }

            return $this->successResult(null, 'Ticket cerrado exitosamente');
        });
    }
}
