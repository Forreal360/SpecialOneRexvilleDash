<?php

namespace App\Livewire\V1\Auth;

use Livewire\Component;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;
use App\Actions\V1\Auth\LoginWithEmailAction;
use App\Livewire\Concerns\HandlesActionResults;
use Illuminate\Support\Facades\Auth;

class LoginComponent extends Component
{
    use HandlesActionResults;

    public $email;
    public $password;

    private $loginWithEmailAction;

    public function boot(LoginWithEmailAction $loginWithEmailAction){
        $this->loginWithEmailAction = $loginWithEmailAction;
    }

    public function mount()
    {
        if(auth()->check()) {
            return redirect()->route('v1.panel.home');
        }
    }

    public function render()
    {
        return view('v1.auth.login-component')->layout('v1.layouts.auth.main');
    }

    public function login()
    {
        $result = $this->executeAction($this->loginWithEmailAction, [
            'email' => $this->email,
            'password' => $this->password
        ], true);

        if ($result->isSuccess()){
            return $this->redirect(route('v1.panel.home'), navigate:true);
        }
    }
}
