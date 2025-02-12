<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Basket;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Illuminate\Http\Request;

class BasketController extends Controller
{
    //Go to the basket
    public function goBasket(){
        $user = Auth::user(); //Get the user
        $basket = Basket::with('product', 'user')->find($id); //Get the basket
        $isAuthenticated = Auth::check(); //Check if the user is authenticated
        return Inertia::render("Carrito", //Render the basket view
        ["basket" => $basket, //Pass the basket to the view
        "isAuthenticated" => $isAuthenticated, //Pass the isAuthenticated to the view
        "user" => $user //Pass the user to the view
    ]);
    }

    //Get all baskets
    public function getAllBaskets()
    {
        $userId = Auth::id(); //Get the user id
        $isAuthenticated = Auth::check(); //Check if the user is authenticated

        // Get the products of the user's basket
        $baskets = Basket::where('user_id', $userId)->get();
        
        // Get the complete products
        $products_baskets = [];
        foreach ($baskets as $basket) {
            $product = Product::with('category') //Get the product with the category
                ->where('id', $basket->product_id) //Get the product with the id
                ->first(); //Get the first product
            
            if ($product) {
                $products_baskets[] = $product; //Add the product to the products_baskets array
            }
        }

        return Inertia::render("Cart", [ //Render the cart view
            "products_baskets" => $products_baskets, //Pass the products_baskets to the view
            "isAuthenticated" => $isAuthenticated //Pass the isAuthenticated to the view
        ]);
    }

    //Delete a product from the basket
    public function delete($product_id)
    {
        $userId = Auth::id(); //Get the user id
        $basket = Basket::where([ //Get the basket
            'product_id' => $product_id,
            'user_id' => $userId,
        ]);
        $basket->delete(); //Delete the basket
        return $basket; //Return the basket
    }
}
