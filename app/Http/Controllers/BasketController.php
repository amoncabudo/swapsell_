<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Basket;

use Illuminate\Http\Request;

class BasketController extends Controller
{
    public function goBasket(){
        $basket = Basket::with('product_id', 'user')->find($id);
        $isAuthenticated = Auth::check();
        return Inertia::render("Carrito",
        ["basket" => $basket,
        "isAuthenticated" => $isAuthenticated,
        "user" => $basket->user
    ]);
    }

}

