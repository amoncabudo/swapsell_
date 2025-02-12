<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class ControllerLegal extends Controller
{
    public function index()
    {
        return Inertia::render('Legal'); //Return the legal view
    }
}

