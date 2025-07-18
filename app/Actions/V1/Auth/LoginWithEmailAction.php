<?php

declare(strict_types=1);

namespace App\Actions\V1\Auth;

use App\Actions\V1\Action;
use App\Exceptions\ValidationErrorException;
use App\Support\ActionResult;
use Illuminate\Support\Facades\DB;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;
use App\Services\V1\AdminService;

class LoginWithEmailAction extends Action
{
    /**
     * Constructor - Inject dependencies here
     */
    public function __construct(private AdminService $adminService)
    {
        // Inject services here
        // Example: $this->service = $service;
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
            "email" => "required|email",
            "password" => "required",
            "timezone" => "required",
        ], [
            // 'field.required' => 'El campo es obligatorio',
        ]);

        $user = $this->adminService->findBy("email", $validated['email']);

        if (!$user) {
            throw new ValidationErrorException([
                'email' => [trans('auth.failed')]
            ]);
        }

        if (!Hash::check($validated['password'], $user->password)) {
            throw new ValidationErrorException([
                'password' => [trans('auth.failed')]
            ]);
        }

        auth()->guard('admin')->login($user);

        session()->put('timezone', $validated['timezone']);

        return $this->successResult(
            data: $user, // Replace with your actual result data
            message: 'Operación completada exitosamente'
        );
    }
}
