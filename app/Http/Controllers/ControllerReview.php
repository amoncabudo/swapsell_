<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use App\Models\User;
use App\Models\Product;
use App\Models\Review;
use App\Models\Transaction;

class ControllerReview extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        
        $transaction = Transaction::with(['product', 'user', 'buyer'])->get();

        return Inertia::render('Review', [
            'isAuthenticated' => Auth::check(),
            'transaction' => $transaction
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $rating = $request->get("rating");
        $comment = $request->get("comment");

        $review = new Review();
        $review->rating = $rating;
        $review->comment = $comment;
        $review->user_id = $request->user_id;
        $review->product_id = 1;
        // dd($review);
        $review->save();
        return redirect()->route('Products')->with('success', 'Producte publicat correctament');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
