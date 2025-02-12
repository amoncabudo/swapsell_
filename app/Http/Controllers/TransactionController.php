<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([ //Validate the request
            'product_id' => 'required|exists:products,id', //Validate the product id
            'category_id' => 'required|exists:categories,id', //Validate the category id
            'name' => 'required|string', //Validate the name
            'description' => 'required|string', //Validate the description
            'price' => 'required|numeric', //Validate the price
            'longitude' => 'required|string', //Validate the longitude
            'latitude' => 'required|string', //Validate the latitude
            'image' => 'required|string', //Validate the image
            'status' => 'required|string' //Validate the status
        ]);

        $product = Product::findOrFail($validated['product_id']); //Get the product

        $transaction = Transaction::create([ //Create the transaction
            'user_id' => $product->user_id, //Set the user id
            'product_id' => $product->id, //Set the product id
            'buyer_id' => Auth::id(), //Set the buyer id
            'category_id' => $validated['category_id'], //Set the category id
            'name' => $validated['name'], //Set the name
            'description' => $validated['description'], //Set the description
            'price' => $validated['price'], //Set the price
            'longitude' => $validated['longitude'], //Set the longitude
            'latitude' => $validated['latitude'], //Set the latitude
            'image' => $validated['image'], //Set the image
            'status' => $validated['status'], //Set the status
            'bid' => false //Set the bid
        ]);

        $product->status = false; //Set the status
        $product->save(); //Save the product

        return response()->json([
            'success' => true, //Set the success
            'message' => 'Transacció creada correctament', //Set the message
            'transaction' => $transaction //Set the transaction
        ]);
    }
}
