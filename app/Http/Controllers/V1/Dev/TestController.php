<?php

namespace App\Http\Controllers\V1\Dev;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Notification;
use App\Notifications\TestNofication;
use Illuminate\Http\Request;
use App\Models\Admin;
use Illuminate\Support\Facades\Password;
use App\Models\Client;
use App\Notifications\ClientRegisterNotification;
use App\Models\Appointment;

class TestController extends Controller
{
    public function index(){

        $roles = \Spatie\Permission\Models\Permission::where('guard_name', 'panel')
            ->get()
            ->groupBy('module');
        dd($roles);
    }
}
