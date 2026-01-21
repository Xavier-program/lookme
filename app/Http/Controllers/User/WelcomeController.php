<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;

class WelcomeController extends Controller
{
    public function index()
    {
        return view('user.welcome');
    }
}
