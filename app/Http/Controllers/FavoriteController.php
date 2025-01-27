<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class FavoriteController extends Controller
{
    public function gatAllFavorites()
    {
        $products_favs = Product::where('favorites',1)->get();
        $isAuthenticated = Auth::check();
        return Inertia::render("Favorites",
        ["products_favs" => $products_favs,
        "isAuthenticated" => $isAuthenticated
    ]);
    }
}
