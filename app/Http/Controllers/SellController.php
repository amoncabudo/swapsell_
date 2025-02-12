<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;

class SellController extends Controller
{

     public function index() //Show the sell page
     {   
         $isAuthenticated = Auth::check(); //Check if the user is authenticated
         return Inertia::render('Sell', [ //Render the sell page
             'name' => Auth::user(), //Set the name of the user
             'isAuthenticated' => $isAuthenticated //Set the is authenticated
         ]);
     }
     
    public function create() //Show the sell page
    {
        return Inertia::render('sell', [ //Render the sell page
            'name' => Auth::user() //Set the name of the user
        ]);
    }

    public function store(Request $request) //Store the product
    {
        $validated = $request->validate([ //Validate the request
            'title' => 'required|string|max:255', //Validate the title
            'description' => 'required|string', //Validate the description
            'price' => 'required|numeric|min:0', //Validate the price
            'category' => 'required|string', //Validate the category
            'condition' => 'required|string', //Validate the condition
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048' //Validate the images
        ]);

        $product = Product::create([ //Create the product
            'user_id' => Auth::id(), //Set the user id
            'name' => $validated['title'], //Set the name
            'description' => $validated['description'], //Set the description
            'price' => $validated['price'], //Set the price
            'status' => $validated['condition'], //Set the condition
            'category_id' => 1, //Set the category id
            'longitude' => '0', //Set the longitude
            'latitude' => '0',  //Set the latitude
            'image' => 'default.jpg' //Set the image
        ]);

        return redirect()->route('products')->with('success', 'Producto publicado exitosamente'); //Redirect to the products page
    }
} 