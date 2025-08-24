<?php

declare(strict_types=1);

namespace App\Actions\V1\Client;

use App\Actions\V1\Action;
use App\Support\ActionResult;
use App\Services\V1\ClientService;
use App\Exceptions\ValidationErrorException;
use App\Http\Resources\V1\ClientResource;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use OpenApi\Annotations as OA;

class UpdateClientAction extends Action
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
        $this->validatePermissions([
            "clients.update"
        ]);

        $validated = $this->validateData($data, [
            "id" => "required|exists:clients,id",
            "name" => "required|string|max:255",
            "last_name" => "required|string|max:255",
            "email" => "required|email|unique:clients,email," . $data['id'],
            "phone_code" => "required|numeric|min_digits:1|max_digits:5",
            "phone" => "required|numeric|min_digits:1|max_digits:10",
            "license_number" => "required|string|max:255",
            "status" => "required|in:A,I",
        ]);

        return DB::transaction(function () use ($validated) {

            $this->clientService->update((int) $validated['id'], $validated);

            return $this->successResult();

        });

    }
}
