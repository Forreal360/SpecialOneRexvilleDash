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

class TestController extends Controller
{
    public function index(){

        $client = Client::where('email', 'joanmilla21@gmail.com')->first();

        return view('v1.mails.client.register-mail', [
            'notifiable' => $client,
            'password' => '123456'
        ]);
        

        $client->notify(new ClientRegisterNotification($client, '123456'));

        dd('Notification sent successfully.');


        return view('v1.dev.test');
    }
}
