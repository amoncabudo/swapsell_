<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class ControllerFooter extends Controller
{
    public function footer()
    {
        return Inertia::render('footer');
    }
}
