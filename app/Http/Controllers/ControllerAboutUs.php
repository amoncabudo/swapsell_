<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
class ControllerAboutUs extends Controller
{
    public function aboutus()
    {
        return Inertia::render('AboutUs');
    }
}
