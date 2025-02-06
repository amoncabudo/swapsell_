<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Product;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use app\http\Controller\FavoriteController;

class ProductController extends Controller
{
   

    public function addProduct(Request $request){
        $name = $request->get("name");
        $description = $request->get("description");
        $price = $request->get("price");
        $longitude = $request->get("longitude");
        $latitude = $request->get("latitude");
        $status = $request->get("status");
        $category_id = $request->get("category_id");
        
        $product = new Product();
        $product->name = $name;
        $product->description = $description;
        $product->price = $price;
        $product->longitude = $longitude;
        $product->latitude = $latitude;
        $product->status = $status;
        $product->user_id = Auth::id();
        $product->category_id = $request->category_id;
        //Image
        
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->storeAs('public', $request->file('image')->getClientOriginalName());
            $product->image = $request->file('image')->getClientOriginalName();
        } else {
            $product->image = 'default.jpg';
        }   
                
        $product->save();
        return redirect()->route('Products')->with('success', 'Producte publicat correctament');
    }

    public function deleteProduct($id){
        $product = Product::find($id);
        $product->delete();
        return redirect()->route('profile')->with('success', 'Producte eliminat correctament');
    }
    
        public function updateProduct(Request $request, $id){
            $product = Product::find($id);
        
            if (!$product) {
                return redirect()->route('Products')->with('error', 'Producte no trobat');
            }
        
            $name = $request->get("name");
            $description = $request->get("description");
            $price = $request->get("price");
            $longitude = $request->get("longitude");
            $latitude = $request->get("latitude");
            $status = $request->get("status");
            $category = $request->get("category");
        
            $product->name = $request->get("name", $product->name);
            $product->description = $request->get("description", $product->description);
            $product->price = $request->get("price", $product->price);
            $product->longitude = $request->get("longitude", $product->longitude);
            $product->latitude = $request->get("latitude", $product->latitude);
            $product->status = $request->get("status", $product->status);
            $product->category_id = $request->get("category_id", $product->category_id);
            
        
            $product->user_id = Auth::id();
            $product->image = 'default.jpg';
            
            $product->save();
            
            return redirect()->route('profile');

        }

    public function getAllProducts(){
        $products = Product::where('bid', false)->with('category')->get();
        $isAuthenticated = Auth::check();
        return Inertia::render("Products", 
        ["products" => $products,
        "isAuthenticated" => $isAuthenticated
    ]);
    }

    public function toggleFavourite(Request $request)
    {
        $id=$request->id;
        // Busca el producto por ID
        $product = Product::find($id);

        if ($product->favorites == 0){
            $product->favorites = 1;
        }else{
            $product->favorites = 0;
            
            // Llamada a la función delete del FavoriteController
            $favoriteController = new \App\Http\Controllers\FavoriteController();
            $favoriteController->delete($product->id); // Asegúrate de pasar el ID correcto del producto
        }
        
        $product->save();

        return $product;
    }

    public function getProductsByCategoryId()
    {
        $products = Product::where('category_id', 7)->get();
        return $products;
    }
    public function mapa(){

        $products = Product::all();
        return Inertia::render('Mapa',[
            'products' => $products
        ]);
    }

    public function goProduct($id){
        $product = Product::with('category', 'user')->find($id);
        $isAuthenticated = Auth::check();
        return Inertia::render("ProducteAmpliat", 
        ["product" => $product,
        "isAuthenticated" => $isAuthenticated,
        "user" => $product->user
    ]);
    }

    public function auction()
    {
        $products = Product::with('auction')
            ->where('bid', true)
            ->get()
            ->map(function($product) {
                // Add remaining time calculation
                if ($product->auction) {
                    $endTime = new \DateTime($product->auction->end_time);
                    $now = new \DateTime();
                    $interval = $now->diff($endTime);
                    
                    $product->auction->remaining = [
                        'days' => $interval->d,
                        'hours' => $interval->h,
                        'minutes' => $interval->i,
                        'seconds' => $interval->s,
                        'total_seconds' => $endTime->getTimestamp() - $now->getTimestamp()
                    ];
                }
                return $product;
            });
        
        return Inertia::render('Subasta', [
            'isAuthenticated' => auth()->check(),
            'products' => $products
        ]);
    }
    public function editProduct($id) {
        $product = Product::find($id);
    
        if (!$product) {
            return redirect()->route('Products')->with('error', 'Producte no trobat');
        }
    
        return Inertia::render('UpdateProduct', ['product' => $product]);
    }

    public function getAvailableProducts()
    {
        $availableProducts = Product::where('user_id', auth()->id())
                              ->where('bid', false)
                              ->get();
    
        return response()->json($availableProducts);
    }

    public function showAuctions(){
        $products = Product::where('bid', true)->get();
        return Inertia::render('Subasta', [
            'products' => $products,
            'isAuthenticated' => Auth::check()
        ]);
        }
        public function getProductsList()
        {
            $products = Product::all();
            return response()->json($products);
        }
        public function store(Request $request)
{
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'description' => 'required|string',
        'price' => 'required|numeric|min:0',
        'status' => 'required|string',
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
    ]);

    $product = new Product();
    $product->name = $validated['name'];
    $product->description = $validated['description'];
    $product->price = $validated['price'];
    $product->status = $validated['status'];
    $product->longitude = $request->longitude;
    $product->latitude = $request->latitude;
    $product->user_id = Auth::id();

    if ($request->hasFile('image')) {
        $imagePath = $request->file('image')->store('public');
        $product->image = basename($imagePath);
    }

    $product->save();

    return response()->json(['success' => true, 'message' => 'Producto creado exitosamente']);
}

public function list()
{
    try {
        // Verificar si el usuario está autenticado
        $user = Auth::user();
        if (!$user) {
            return response()->json(['error' => 'Usuario no autenticado'], 401);
        }

        // Obtener productos asociados al usuario
        $products = Product::where('user_id', $user->id)->get();
        return response()->json($products);
    } catch (\Exception $e) {
        // Manejo de errores
        return response()->json(['error' => 'Error al cargar los productos: ' . $e->getMessage()], 500);
    }
}


}
