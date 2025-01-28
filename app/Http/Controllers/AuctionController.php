<?php

namespace App\Http\Controllers;

use App\Models\Auction;
use App\Models\Product; // Add this import
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

    public function store(Request $request)
    {   
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'start_price' => 'required|numeric|min:0',
            'current_price' => 'required|numeric|min:0',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            'status' => 'required|boolean'
        ]);

        $auction = Auction::create([
            'product_id' => $validated['product_id'],
            'start_price' => $validated['start_price'],
            'current_price' => $validated['current_price'],
            'start_time' => $validated['start_time'],
            'end_time' => $validated['end_time'],
            'status' => $validated['status']
        ]);

        $product = Product::find($validated['product_id']);
        $product->update(['bid' => true]);

        return redirect()->route('auction');
    }
}