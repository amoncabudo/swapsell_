<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
class ControllerFavorites extends Controller
{
    public function favorites()
    {
        return Inertia::render('favorites');
    }
}
