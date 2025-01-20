<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class ControllerContact extends Controller
{
    public function contact()
    {
        return Inertia::render('Contact');
    }
}

