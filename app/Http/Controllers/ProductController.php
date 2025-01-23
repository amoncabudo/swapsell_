<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Product;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class ProductController extends Controller
{
    public function index()
    {
        return Inertia::render('Products');
    }

    public function addProduct(Request $request){
        $name = $request->get("name");
        $description = $request->get("description");
        $price = $request->get("price");
        $longitude = $request->get("longitude");
        $latitude = $request->get("latitude");
        $status = $request->get("status");

        $product = new Product();
        $product->name = $name;
        $product->description = $description;
        $product->price = $price;
        $product->longitude = $longitude;
        $product->latitude = $latitude;
        $product->status = $status;
        $product->user_id = Auth::id();
        $product->category_id = 1;
        $product->image = 'default.jpg';

        $product->save();
        
        return redirect()->route('Products')->with('success', 'Producte publicat correctament');
    }

    public function deleteProduct($id){
        $product = Product::find($id);
        $product->delete();
        return redirect()->route('Products')->with('success', 'Producte eliminat correctament');
    }
    
    public function updateProduct(Request $request){
        $id = $request->get("id");
        $name = $request->get("name");
        $description = $request->get("description");
        $price = $request->get("price");
        $longitude = $request->get("longitude");
        $latitude = $request->get("latitude");
        $status = $request->get("status");
        $category = $request->get("category");

        $product = Product::find($id);
        $product->name = $request->get("name", $product->name);
        $product->description = $request->get("description", $product->description);
        $product->price = $request->get("price", $product->price);
        $product->longitude = $request->get("longitude", $product->longitude);
        $product->latitude = $request->get("latitude", $product->latitude);
        $product->status = $request->get("status", $product->status);

        $product->user_id = Auth::id();
        $product->category_id = $category;
        $product->image = 'default.jpg';

        $product->save();
        
        return redirect()->route('Products')->with('success', 'Producte actualitzat correctament');
    }

    public function getAllProducts(){
        $products = Product::all();
        return Inertia::render("Products", ["products" => $products]);
    }

}
