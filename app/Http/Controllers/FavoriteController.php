<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use App\Models\Favorite;

class FavoriteController extends Controller
{
    public function getAllFavorites()
    {
        $products_favs = Product::where('favorites',1)->get();
        $isAuthenticated = Auth::check();
        $userId = Auth::id();
        //dd($products_favs->toArray());

        foreach ($products_favs as $product) {
            $exists = Favorite::where('user_id', $userId)
            ->where('product_id', $product->id)
            ->exists();

            if (!$exists)
            {
            Favorite::addFavorite($product->id, $userId);
            }
        }
        return Inertia::render("Favorites",
        ["products_favs" => $products_favs,
        "isAuthenticated" => $isAuthenticated
    ]);
    }


    function delete($product_id){
        $userId = Auth::id();
        $product=Favorite::where([
            'product_id' => $product_id,
            'user_id' => $userId,
        ]);
        $product->delete(); 
        return $product;
    }
   
    
}
