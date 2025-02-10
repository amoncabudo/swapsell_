<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Inertia\Inertia;

class WelcomeController extends Controller
{
    public function index()
    {
        $featuredProducts = Product::take(4)->get(); // Obtener los 4 productos
        return Inertia::render('Welcome', [
            'featuredProducts' => $featuredProducts,
        ]);
    }
} 