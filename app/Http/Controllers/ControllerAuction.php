<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
class ControllerAuction extends Controller
{
    public function auctions()
    {
        return Inertia::render('auctions');
    }
}
