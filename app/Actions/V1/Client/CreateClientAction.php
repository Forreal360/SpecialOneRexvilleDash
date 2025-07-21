<?php

declare(strict_types=1);

namespace App\Actions\V1\Client;

use App\Actions\V1\Action;
use App\Support\ActionResult;
use Illuminate\Support\Facades\DB;
use App\Services\V1\ClientService;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class CreateClientAction extends Action
{

    public function __construct(private ClientService $clientService)
    {

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
            "name" => "required|string|max:255",
            "last_name" => "required|string|max:255",
            "email" => "required|email|unique:clients,email",
            "phone_code" => "required|numeric|min_digits:1|max_digits:5",
            "phone" => "required|numeric|min_digits:1|max_digits:10",
            "license_number" => "required|string|max:255",
            "status" => "required|in:A,I",
        ]);

        $randomPassword = Str::random(10);
        $validated['password'] = Hash::make($randomPassword);

        return DB::transaction(function () use ($validated) {

            $client = $this->clientService->create($validated);


            #send email to client
            $client->notify(new ClientRegisterNotification($client, $randomPassword));

            return $this->successResult();
        });
    }
}
