<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Inertia\Inertia;

class WelcomeController extends Controller
{
    public function index()
    {
        $featuredProducts = Product::take(4)->get(); //Get the 4 featured products
        return Inertia::render('Welcome', [ //Render the welcome page
            'featuredProducts' => $featuredProducts, //Set the featured products
        ]);
    }
} 