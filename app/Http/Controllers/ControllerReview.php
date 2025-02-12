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
    //Show the review view
    public function index()
{
    $userId = Auth::id(); //Get the user id
    $transaction = Transaction::with(['product', 'user', 'buyer']) //Get the transaction with the product, user and buyer
        ->where('buyer_id', $userId) //Where the buyer id is the user id
        ->get(); //Get the transactions

    return Inertia::render('Review', [ //Return the review view
        'isAuthenticated' => Auth::check(), //Check if the user is authenticated
        'transaction' => $transaction, //Get the transactions
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
        $rating = $request->get("rating"); //Get the rating
        $comment = $request->get("comment"); //Get the comment

        $review = new Review(); //Create a new review
        $review->rating = $rating; //Set the rating
        $review->comment = $comment; //Set the comment
        $review->user_id = $request->user_id; //Set the user id
        $review->product_id = 1; //Set the product id
        // dd($review);
        $review->save(); //Save the review
        return redirect()->route('Products')->with('success', 'Producte publicat correctament'); //Redirect to the products view with the success message
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
