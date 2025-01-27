<?php

namespace App\Http\Controllers;

use App\Models\Auction;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;

class AuctionController extends Controller
{
    

    public function index()
    {
        $auctions = Auction::active()->get();

        return Inertia::render('Auctions', [
            'auctions' => $auctions,
            'isAuthenticated' => Auth::check()
        ]);
    }

    public function create()
    {
        return Inertia::render('FormSubasta', [
            'isAuthenticated' => Auth::check(),
            'userProducts' => Auth::user()->products
        ]);
    }
}