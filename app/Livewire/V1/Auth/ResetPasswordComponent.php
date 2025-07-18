<?php

namespace App\Livewire\V1\Auth;

use Livewire\Component;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use App\Models\Admin;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Str;

class ResetPasswordComponent extends Component
{
    public $email;
    public $password;
    public $password_confirmation;
    public $token;

    public function mount($token)
    {
        $this->token = $token;
    }

    public function render()
    {
        return view('v1.auth.reset-password-component')->layout('v1.layouts.auth.main');
    }

    public function resetPassword()
    {
        $this->validate([
            'token' => 'required',
            'email' => 'required|email|exists:admins,email',
            'password' => 'required|min:8|confirmed',
        ], [
            'email.exists' => 'No encontramos un usuario con esa dirección de correo electrónico.',
            'password.confirmed' => 'Las contraseñas no coinciden.',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
        ]);

        $status = Password::broker('admins')->reset(
            [
                'email' => $this->email,
                'password' => $this->password,
                'password_confirmation' => $this->password_confirmation,
                'token' => $this->token,
            ],
                            function (Admin $user, string $password) {
                    $user->forceFill([
                        'password' => $password
                    ])->setRememberToken(Str::random(60));

                $user->save();

                event(new PasswordReset($user));
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            session()->flash('status', trans('passwords.reset'));
            return redirect()->route('login');
        } else {
            $this->addError('email', trans($status));
        }
    }
}
