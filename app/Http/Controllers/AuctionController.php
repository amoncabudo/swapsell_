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
    
    //Index of auctions
    public function index()
    {
        $auctions = Auction::active()->get(); //Get all active auctions
        $products = Product::all(); //Get all products
        return Inertia::render('Auctions', [ //Render the auctions view
            'auctions' => $auctions, //Pass the auctions to the view
            'products' => $products, //Pass the products to the view
            'isAuthenticated' => Auth::check() //Check if the user is authenticated
        ]);
    }

    //Create a new auction
    public function create()
    {
        return Inertia::render('FormSubasta', [ //Render the form subasta view
            'isAuthenticated' => Auth::check(), //Check if the user is authenticated
            'userProducts' => Auth::user()->products //Get the user's products
        ]);
    }

    //Store a new auction
    public function store(Request $request)
    {   
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id', //Validate the product id
            'start_price' => 'required|numeric|min:0', //Validate the start price
            'current_price' => 'required|numeric|min:0', //Validate the current price
            'start_time' => 'required|date', //Validate the start time
            'end_time' => 'required|date|after:start_time', //Validate the end time
            'status' => 'required|boolean' //Validate the status
        ]);
        
        //Creathe the auction with the validated data
        $auction = Auction::create([
            'product_id' => $validated['product_id'],
            'user_id' => Auth::id(),
            'start_price' => $validated['start_price'],
            'current_price' => $validated['current_price'],
            'start_time' => $validated['start_time'],
            'end_time' => $validated['end_time'],
            'status' => $validated['status']
        ]);

        //Update the product bid to true
        $product = Product::find($validated['product_id']);
        $product->update(['bid' => true]);

        return redirect()->route('auction'); //Redirect to the auction index
    }

    //Bid on an auction
    public function bid(Request $request)
    {
        $validated = $request->validate([
            'auction_id' => 'required|exists:auctions,id', //Validate the auction id
            'bid_amount' => 'required|numeric|min:0' //Validate the bid amount
        ]);

        $auction = Auction::findOrFail($validated['auction_id']); //Find the auction

        // Verify that the bid is greater than the current price
        if ($validated['bid_amount'] <= $auction->current_price) {
            return back()->withErrors(['bid_amount' => 'La puja debe ser mayor al precio actual']);
        }

        // Update the current price and the last bidder
        $auction->update([
            'current_price' => $validated['bid_amount'],
            'last_bidder_id' => Auth::id() // Add the current user's ID as the last bidder
        ]);

        return back()->with('success', 'Licitació realitzada amb èxit');
    }

    //Finish an auction
    public function finish($id)
    {
        $auction = Auction::with('lastBidder')->findOrFail($id); //Find the auction
        
        if ($auction->status) {
            // Update the auction status to finished
            $auction->update(['status' => false]);
            
            // If there is a winner, create the record in the winner table with the next data
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
                'message' => 'Subhasta finalitzada exitosament'
            ]);
        }
        
        return response()->json([
            'message' => 'La subhasta ja estava finalitzada'
        ]);
    }

    public function auction()
    {   
        $products = Product::with(['auction.lastBidder'])
            ->where('bid', true)
            ->leftJoin('auctions', 'products.id', '=', 'auctions.product_id')
            ->whereDate('end_time', '>=', date('Y-m-d H-i-s', strtotime('-1 week')))
            ->select('products.*')  // Importante: seleccionar solo las columnas de products
            ->get()
            ->map(function($product) {
                if ($product->auction) {
                    $endTime = new \DateTime($product->auction->end_time);
                    $now = new \DateTime();
                    $interval = $now->diff($endTime);
                    
                    $product->auction->remaining = [
                        'days' => $interval->d,
                        'hours' => $interval->h,
                        'minutes' => $interval->i,
                        'seconds' => $interval->s,
                        'total_seconds' => $endTime->getTimestamp() - $now->getTimestamp()
                    ];
                }
                return $product;
            });
        
        return Inertia::render('Subasta', [
            'isAuthenticated' => auth()->check(),
            'products' => $products
        ]);
    }

    public function deleteAuction($id)
    {
        $auction = Auction::find($id);
        $auction->delete();
        return redirect()->route('profile');
    }

}