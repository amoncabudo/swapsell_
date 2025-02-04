<?php

namespace App\Http\Controllers;

use App\Models\Auction;
use App\Models\Product; // Add this import
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use App\Models\Winner;

class AuctionController extends Controller
{
    

    public function index()
    {
        $auctions = Auction::active()->get();
        $products = Product::all();
        return Inertia::render('Auctions', [
            'auctions' => $auctions,
            'products' => $products,
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
            'user_id' => Auth::id(),
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

    public function bid(Request $request)
    {
        $validated = $request->validate([
            'auction_id' => 'required|exists:auctions,id',
            'bid_amount' => 'required|numeric|min:0'
        ]);

        $auction = Auction::findOrFail($validated['auction_id']);

        // Verificar que la puja sea mayor que el precio actual
        if ($validated['bid_amount'] <= $auction->current_price) {
            return back()->withErrors(['bid_amount' => 'La puja debe ser mayor al precio actual']);
        }

        // Actualizar el precio actual y el último pujador
        $auction->update([
            'current_price' => $validated['bid_amount'],
            'last_bidder_id' => Auth::id() // Agregamos el ID del usuario actual como último pujador
        ]);

        return back()->with('success', 'Puja realizada con éxito');
    }

    public function finish($id)
    {
        $auction = Auction::with('lastBidder')->findOrFail($id);
        
        if ($auction->status) {
            // Actualizar el estado de la subasta a finalizada
            $auction->update(['status' => false]);
            
            // Si hay un ganador, crear el registro en la tabla winner
            if ($auction->last_bidder_id) {
                Winner::create([
                    'product_id' => $auction->product_id,
                    'user_id' => $auction->last_bidder_id,
                    'amount' => $auction->current_price,
                    'winner_date' => now()
                ]);
            }
            
            return response()->json([
                'winner' => $auction->lastBidder,
                'message' => 'Subasta finalizada exitosamente'
            ]);
        }
        
        return response()->json([
            'message' => 'La subasta ya estaba finalizada'
        ]);
    }
}