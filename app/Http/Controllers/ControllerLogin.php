<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class ControllerLogin extends Controller
{
    public function index()
    {
        return Inertia::render('Auth/Login'); //Return the login view with auth
    }
}
