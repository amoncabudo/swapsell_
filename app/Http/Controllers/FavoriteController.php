<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use App\Models\Favorite;

class FavoriteController extends Controller
{
    public function getAllFavorites() //Get all the favorites
    {
        $userId = Auth::id(); //Get the user id
        $isAuthenticated = Auth::check(); //Check if the user is authenticated

        // Get the favorites of the user 
        $favorites = Favorite::where('user_id', $userId)->get();
        
        // Get the products of the favorites
        $products_favs = [];
        foreach ($favorites as $favorite) {
            $product = Product::with('category') //Get the category of the product
                ->where('id', $favorite->product_id) //Get the product id
                ->first(); //Get the first product
            
            if ($product) {
                $products_favs[] = $product; //Add the product to the products_favs array
            }
        }

        return Inertia::render("Favorites", [
            "products_favs" => $products_favs, //Set the products_favs
            "isAuthenticated" => $isAuthenticated //Set the isAuthenticated
        ]);
    }


    function delete($product_id){
        $userId = Auth::id(); //Get the user id
        $product=Favorite::where([ //Get the product
            'product_id' => $product_id, //Get the product id
            'user_id' => $userId, //Get the user id
        ]);
        $product->delete(); //Delete the product
        return $product; //Return the product
    }
   
    
}
