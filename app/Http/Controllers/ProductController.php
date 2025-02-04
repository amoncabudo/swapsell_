<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Product;
use App\Models\Category;
use App\Models\Favorite;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use App\Http\Controllers\FavoriteController;
use Carbon\Carbon;

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
        $userId = Auth::id();
        $isAuthenticated = Auth::check();

        // Obtener los productos
        $products = Product::where('bid', false)
            ->where('user_id', '!=', $userId)
            ->with('category')
            ->get();

        // Si el usuario está autenticado, verificar cuáles son favoritos
        if ($isAuthenticated) {
            $favorites = Favorite::where('user_id', $userId)->pluck('product_id')->toArray();
            
            foreach ($products as $product) {
                $product->is_favorite = in_array($product->id, $favorites);
            }
        }

        return Inertia::render("Products", [
            "products" => $products,
            "isAuthenticated" => $isAuthenticated
        ]);
    }

    public function toggleFavourite(Request $request)
    {
        $productId = $request->id;
        $userId = Auth::id();

        // Verificar si ya existe en favoritos
        $exists = Favorite::where('user_id', $userId)
                         ->where('product_id', $productId)
                         ->exists();

        if (!$exists) {
            // Añadir a favoritos
            Favorite::create([
                'user_id' => $userId,
                'product_id' => $productId
            ]);
            return response()->json(['is_favorite' => true]);
        } else {
            // Eliminar de favoritos
            Favorite::where('user_id', $userId)
                   ->where('product_id', $productId)
                   ->delete();
            return response()->json(['is_favorite' => false]);
        }
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

    private function calcularTiempoTranscurrido($fecha)
    {
        $fecha = Carbon::parse($fecha);
        $ahora = Carbon::now();
        
        $diferencia = (int)$fecha->diffInSeconds($ahora);
        
        if ($diferencia < 60) {
            return "Hace " . $diferencia . " segundo" . ($diferencia == 1 ? '' : 's');
        }
        
        $diferencia = (int)$fecha->diffInMinutes($ahora);
        if ($diferencia < 60) {
            return "Hace " . $diferencia . " minuto" . ($diferencia == 1 ? '' : 's');
        }
        
        $diferencia = (int)$fecha->diffInHours($ahora);
        if ($diferencia < 24) {
            return "Hace " . $diferencia . " hora" . ($diferencia == 1 ? '' : 's');
        }
        
        $diferencia = (int)$fecha->diffInDays($ahora);
        return "Hace " . $diferencia . " día" . ($diferencia == 1 ? '' : 's');
    }

    
    public function goProduct($id){
        $product = Product::with('category', 'user')->find($id);
        $isAuthenticated = Auth::check();
        $comments = $product->comments()
        ->with('user')
        ->orderBy('created_at', 'desc')
        ->get()
        ->map(function ($comment) {
            $comment->tiempo_transcurrido = $this->calcularTiempoTranscurrido($comment->created_at);
            return $comment;
        });        return Inertia::render("ProducteAmpliat", 
        ["product" => $product,
        "isAuthenticated" => $isAuthenticated,
        "user" => $product->user,
        "commentarios" => $comments
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

    

    public function showcomment(Request $request)
    {
        $product = Product::find($request->product_id);
        $product->comments()->create([
            'user_id' => auth()->id(),
            'content' => $request->content
        ]);
    
        return response()->json($product->comments()->with('user')->get());
    }
}
