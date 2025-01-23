<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;

class SellController extends Controller
{
    /**
     * Muestra el formulario de venta
     */

     public function index()
     {   
         $isAuthenticated = Auth::check();
         return Inertia::render('Sell', [
             'name' => Auth::user(),
             'isAuthenticated' => $isAuthenticated
         ]);
     }
     
    public function create()
    {
        return Inertia::render('sell', [
            'name' => Auth::user()
        ]);
    }

    /**
     * Almacena un nuevo producto
     */
    public function store(Request $request)
    {
        // Validación de los datos del formulario
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'category' => 'required|string',
            'condition' => 'required|string',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        // Crear el producto con los campos correctos
        $product = Product::create([
            'user_id' => Auth::id(),
            'name' => $validated['title'],
            'description' => $validated['description'],
            'price' => $validated['price'],
            'status' => $validated['condition'],
            'category_id' => 1, // Temporal hasta que implementemos categorías
            'longitude' => '0', // Temporal
            'latitude' => '0',  // Temporal
            'image' => 'default.jpg' // Temporal hasta que implementemos subida de imágenes
        ]);

        return redirect()->route('products')->with('success', 'Producto publicado exitosamente');
    }
} 