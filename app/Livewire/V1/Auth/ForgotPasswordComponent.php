<?php

namespace App\Livewire\V1\Auth;

use Livewire\Component;
use Illuminate\Support\Facades\Password;
use App\Models\Admin;

class ForgotPasswordComponent extends Component
{
    public $email;

    public function render()
    {
        return view('v1.auth.forgot-password-component')->layout('v1.layouts.auth.main');
    }

    public function sendResetLink()
    {
        $this->validate([
            'email' => 'required|email|exists:admins,email',
        ]);

        Password::sendResetLink(['email' => $this->email]); // Comentado por ahora
        session()->flash('status', 'Si el correo existe, se enviará un enlace de recuperación.');
    }
}
