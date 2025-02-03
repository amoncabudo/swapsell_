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
        $userId = Auth::id();
        $isAuthenticated = Auth::check();

        // Obtener los IDs de los productos favoritos del usuario
        $favorites = Favorite::where('user_id', $userId)->get();
        
        // Obtener los productos completos
        $products_favs = [];
        foreach ($favorites as $favorite) {
            $product = Product::with('category')
                ->where('id', $favorite->product_id)
                ->first();
            
            if ($product) {
                $products_favs[] = $product;
            }
        }

        return Inertia::render("Favorites", [
            "products_favs" => $products_favs,
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
