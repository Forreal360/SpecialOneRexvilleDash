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

        $appointment = Appointment::find(22);

        $appointment = $appointment->appointmentServices->where('status', 'A')->pluck('service.name')->implode(', ');

        dd($appointment);
    }
}
