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
    public function goBasket(){
        $user = Auth::user();
        $basket = Basket::with('product', 'user')->find($id);
        $isAuthenticated = Auth::check();
        return Inertia::render("Carrito",
        ["basket" => $basket,
        "isAuthenticated" => $isAuthenticated,
        "user" => $user
    ]);
    }

    public function getAllBaskets()
    {
        $userId = Auth::id();
        $isAuthenticated = Auth::check();

        // Obtener los productos del carrito del usuario
        $baskets = Basket::where('user_id', $userId)->get();
        
        // Obtener los productos completos
        $products_baskets = [];
        foreach ($baskets as $basket) {
            $product = Product::with('category')
                ->where('id', $basket->product_id)
                ->first();
            
            if ($product) {
                $products_baskets[] = $product;
            }
        }

        return Inertia::render("Cart", [
            "products_baskets" => $products_baskets,
            "isAuthenticated" => $isAuthenticated
        ]);
    }

    public function delete($product_id)
    {
        $userId = Auth::id();
        $basket = Basket::where([
            'product_id' => $product_id,
            'user_id' => $userId,
        ]);
        $basket->delete(); 
        return $basket;
    }
}
