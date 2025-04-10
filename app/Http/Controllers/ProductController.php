<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Product;
use App\Models\Category;
use App\Models\Favorite;
use App\Models\Basket;
use App\Models\Review;
use App\Models\Auction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use App\Http\Controllers\FavoriteController;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
   //Add a product
    public function addProduct(Request $request){ 
        $name = $request->get("name"); //Get the name
        $description = $request->get("description"); //Get the description
        $price = $request->get("price"); //Get the price
        $longitude = $request->get("longitude"); //Get the longitude
        $latitude = $request->get("latitude"); //Get the latitude
        $category_id = $request->get("category_id"); //Get the category id
        

        $product = new Product(); //Create a new product
        $product->name = $name; //Set the name
        $product->description = $description; //Set the description
        $product->price = $price; //Set the price
        $product->longitude = $longitude; //Set the longitude
        $product->latitude = $latitude; //Set the latitude
        $product->user_id = Auth::id(); //Set the user id
        $product->category_id = $request->category_id; //Set the category id
        
                
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->storeAs('public', $request->file('image')->getClientOriginalName()); //Store the image
            $product->image = $request->file('image')->getClientOriginalName(); //Set the image
        } else {
            $product->image = 'default.jpg'; //Set the image
        }   
                
        $product->save(); //Save the product
        return redirect()->route('Products')->with('success', 'Producte publicat correctament'); //Redirect to the products page
    }

    public function deleteProduct($id){
        $auction = Auction::where('product_id', $id)->first();
        if($auction){
                $auction->delete();  //Delete the auction
        }
        $product = Product::find($id); //Find the product
        $product->delete();
      
        return redirect()->route('profile')->with('success', 'Producte eliminat correctament'); //Redirect to the profile page
    }
    
    public function updateProduct(Request $request, $id){
        $product = Product::find($id);

        if (!$product) {
            return redirect()->route('Products')->with('error', 'Producte no trobat');
        }

        $product->name = $request->get("name", $product->name);
        $product->description = $request->get("description", $product->description);
        $product->price = $request->get("price", $product->price);
        $product->longitude = $request->get("longitude", $product->longitude);
        $product->latitude = $request->get("latitude", $product->latitude);
        $product->category_id = $request->get("category_id", $product->category_id);
        $product->user_id = Auth::id();

        // Manejar la imagen
        if ($request->hasFile('image')) {
            // Eliminar la imagen anterior si no es la default
            if ($product->image !== 'default.jpg') {
                Storage::delete('public/' . $product->image);
            }
            
            $imageName = time() . '_' . $request->file('image')->getClientOriginalName();
            $request->file('image')->storeAs('public', $imageName);
            $product->image = $imageName;
        }

        $product->save();
        
        return redirect()->route('profile');
    }

    public function getAllProducts(){
        $userId = Auth::id(); //Get the user id
        $isAuthenticated = Auth::check(); //Check if the user is authenticated

        $products = Product::where('bid', false) //Get the products
            ->where('user_id', '!=', $userId) //Get the products of the user
            ->where('category_id', '!=', 7) //Get the products of the category
            ->where('status', true) //Get the products of the status
            ->with('category') //Get the category of the product
            ->get();

        // If the user is authenticated, verify which are favorites
        if ($isAuthenticated) {
            $favorites = Favorite::where('user_id', $userId)->pluck('product_id')->toArray(); //Get the favorites of the user
            
            foreach ($products as $product) {
                $product->is_favorite = in_array($product->id, $favorites); //Check if the product is a favorite
            }
        }

        return Inertia::render("Products", [ //Render the products page
            "products" => $products,
            "isAuthenticated" => $isAuthenticated
        ]);
    }

    public function toggleFavourite(Request $request)
    {
        $productId = $request->id;
        $userId = Auth::id();

        //Verify if the product is a favorite
        $exists = Favorite::where('user_id', $userId)
                         ->where('product_id', $productId)
                         ->exists();

        if (!$exists) { //If the product exists
            Favorite::create([ //Create the favorite
                'user_id' => $userId, //Set the user id
                'product_id' => $productId //Set the product id
            ]);
            return response()->json(['is_favorite' => true]); //Return the favorite
        } else { //If the product does not exist
            Favorite::where('user_id', $userId) //Delete the favorite
                   ->where('product_id', $productId)
                   ->delete();
            return response()->json(['is_favorite' => false]); //Return the favorite
        }
    }

    public function getProductsByCategoryId()
    {
        $products = Product::where('category_id', 7)->get(); //Get the products of the category
        return $products;

        
    }
    public function mapa(){
        $isAuthenticated = Auth::check();
        $products = Product::all();
        return Inertia::render('Mapa',[
            'products' => $products,
            'isAuthenticated' => $isAuthenticated
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
        $userId = Auth::id();
        $mediaReview = round(Review::avg('rating'), 2);
        // Verificar si el producto está en el carrito del usuario
        // Asegurarse de que el usuario autenticado tenga una imagen
        $authUser = Auth::user();
        if ($authUser && !$authUser->image) {
            $authUser->image = 'images/default-avatar.png'; // Imagen por defecto
        }
        if ($isAuthenticated) {
            $product->is_basket = \App\Models\Basket::where('user_id', $userId)
                ->where('product_id', $id)
                ->exists();
        }

        if ($product->user){
            $product->user->image = $product->user->image;
        }

        $comments = $product->comments()
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($comment) {
                $comment->tiempo_transcurrido = $this->calcularTiempoTranscurrido($comment->created_at);
                return $comment;
            });
        $authUser=Auth::user();
        return Inertia::render("ProducteAmpliat", [
            "product" => $product,
            "isAuthenticated" => $isAuthenticated,
            "user" => $product->user,
            "commentarios" => $comments,
            "mediaReview" => $mediaReview,
            "authUser" => $authUser
        ]);
    }

    public function editProduct($id) {
        $product = Product::find($id);
    
        if (!$product) {
            return redirect()->route('Products')->with('error', 'Producte no trobat');
        }
        $isAuthenticated = Auth::check();
        return Inertia::render('UpdateProduct',
         ['product' => $product,
         'isAuthenticated' => $isAuthenticated]);
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


    

    public function showcomment(Request $request)
    {
        $product = Product::find($request->product_id);
        $product->comments()->create([
            'user_id' => auth()->id(),
            'content' => $request->content
        ]);
    
        return response()->json($product->comments()->with('user')->get());
    }

    public function toggleBasket(Request $request)
    {
        $productId = $request->id;
        $userId = Auth::id();

        $exists = \App\Models\Basket::where('user_id', $userId)
                         ->where('product_id', $productId)
                         ->exists();

        if (!$exists) {
            // Añadir al carrito
            \App\Models\Basket::create([
                'user_id' => $userId,
                'product_id' => $productId
            ]);
            return response()->json(['is_basket' => true]);
        } else {
            // Eliminar del carrito
            \App\Models\Basket::where('user_id', $userId)
                   ->where('product_id', $productId)
                   ->delete();
            return response()->json(['is_basket' => false]);
        }
    }


}
