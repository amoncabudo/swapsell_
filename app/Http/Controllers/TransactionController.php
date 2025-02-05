<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    public function store(Request $request)
    {
            $validated = $request->validate([
                'product_id' => 'required|exists:products,id',
                'category_id' => 'required|exists:categories,id',
                'name' => 'required|string',
                'description' => 'required|string',
                'price' => 'required|numeric',
                'longitude' => 'required|string',
                'latitude' => 'required|string',
                'image' => 'required|string',
                'status' => 'required|string'
            ]);

            // Obtener el producto para conseguir el ID del vendedor
            $product = Product::findOrFail($validated['product_id']);

            $transaction = Transaction::create([
                'user_id' => $product->user_id, // ID del vendedor
                'buyer_id' => Auth::id(), // ID del comprador
                'category_id' => $validated['category_id'],
                'name' => $validated['name'],
                'description' => $validated['description'],
                'price' => $validated['price'],
                'longitude' => $validated['longitude'],
                'latitude' => $validated['latitude'],
                'image' => $validated['image'],
                'status' => $validated['status'],
                'bid' => false
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Transacció creada correctament',
                'transaction' => $transaction
            ]);
    }
}
