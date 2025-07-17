<?php

namespace App\Http\Controllers\V1\Dev;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TestController extends Controller
{
    public function index(){
        $date = '2025-07-17 10:00:00';
        $timezone = "America/Caracas";
        $date = dateToLocal($date, $timezone);
        dd($date);
    }
}
