<?php

namespace App\Http\Controllers\V1\Dev;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TestController extends Controller
{
    public function index(){
        return view('v1.dev.test');
    }
}
