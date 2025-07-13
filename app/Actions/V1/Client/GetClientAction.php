<?php

declare(strict_types=1);

namespace App\Actions\V1\Client;

use App\Actions\V1\Action;
use App\Support\ActionResult;
use App\Services\V1\ClientService;
use App\Exceptions\ValidationErrorException;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\V1\ClientResource;
use OpenApi\Annotations as OA;

class GetClientAction extends Action
{

    public function __construct(
        private ClientService $clientService
    ) {
        // UserService injected
    }

    /**
     * Handle the action logic
     *
     * @param array|object $data
     * @return ActionResult
     */
    public function handle($data): ActionResult
    {
        // Business logic with transaction
        return DB::transaction(function () {
            // Obtener el usuario autenticado
            $user = auth()->user();

            if (!$user) {
                throw new ValidationErrorException([
                    'auth' => [trans('auth.failed')]
                ]);
            }

            // Return successful result
            return $this->successResult(
                data: [
                    'client' => new ClientResource($user)
                ],
            );
        });
    }
}
