<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class ControllerAboutUs extends Controller
{
    public function AboutUs(){
        return Inertia::render('AboutUs');
    }
}

