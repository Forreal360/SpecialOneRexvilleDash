<?php

namespace App\Http\Controllers\V1\Dev;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Notification;
use App\Notifications\TestNofication;
use Illuminate\Http\Request;
use App\Models\Admin;
use Illuminate\Support\Facades\Password;

class TestController extends Controller
{
    public function index(){

        $admin = Admin::where('email', 'joanmilla21@gmail.com')->first();

        //send password reset notification
        Password::sendResetLink(['email' => $admin->email]);

        dd('Notification sent successfully.');


        return view('v1.dev.test');
    }
}
