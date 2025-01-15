<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
class ControllerEvents extends Controller
{
    public function events()
    {
        return Inertia::render('Events');
    }
}
