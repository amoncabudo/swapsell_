<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Product;
use App\Models\Event;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use App\Http\Controllers\FavoriteController;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class AdminPanelController extends Controller
{
    //add product
    public function AdminAddProduct(Request $request){ 
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

    //add event
    public function AdminAddEvent(Request $request)
    {

        $title = $request->get("title");
        $description = $request->get("description");
        $date = $request->get("date");
        $time = $request->get("time");
        $latitude = $request->get("latitude");
        $longitude = $request->get("longitude");

        $userId = auth()->id();

        $event = new Event();
        $event->title = $title;
        $event->description = $description;
        $event->date = $date;
        $event->time = $time;
        $event->longitude = $longitude;
        $event->latitude = $latitude;
        $event->user_id = $userId;

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('public');
            $event->image = basename($imagePath);
        }
        
        $event->save();


        return redirect()->back()->with('success', 'Esdeveniment publicat correctament');
    }

    //add user
    public function AdminAddUser(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'surname' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'role' => 'required|numeric|between:0,2',
        ]);

        $user = new User([
            'name' => $request->name,
            'surname' => $request->surname,
            'email' => $request->email,
            'role' => $request->role,
            'password' => bcrypt($request->password),
        ]);

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('public/users');
            $user->image = 'storage/users/' . basename($imagePath);
        }

        $user->save();

        return redirect()->back()->with('success', 'Usuario creado correctamente');
    }


    //update product
    public function AdminUpdateProduct(Request $request, $id)
    {
        $product = Product::find($id);
    
        if (!$product) {
            return response()->json(['error' => 'Producte no trobat'], 404);
        }
    
        $product->name = $request->get("name", $product->name);
        $product->description = $request->get("description", $product->description);
        $product->price = $request->get("price", $product->price);
        $product->longitude = $request->get("longitude", $product->longitude);
        $product->latitude = $request->get("latitude", $product->latitude);
        $product->category_id = $request->get("category_id", $product->category_id);
        $product->user_id = Auth::id();
    
        if ($request->hasFile('image')) {
            // Eliminar la imagen anterior si no es la default
            if ($product->image && $product->image !== 'default.jpg') {
                Storage::delete('public/' . $product->image);
            }
            
            // Generar un nombre único para la imagen
            $imageName = time() . '_' . $request->file('image')->getClientOriginalName();
            
            // Guardar la nueva imagen
            $request->file('image')->storeAs('public', $imageName);
            $product->image = $imageName;
        }
    
        $product->save();
        
        return response()->json(['message' => 'Producte actualitzat correctament']);
    }

    //update event
    public function AdminUpdateEvent(Request $request, $id){
        $id = $request->get("id");
        $title = $request->get("title");
        $description = $request->get("description");
        $date = $request->get("date");
        $time = $request->get("time");
        $longitude = $request->get("longitude");
        $latitude = $request->get("latitude");

        $event = Event::find($id);
        $event->title = $request->get("title", $event->title);
        $event->description = $request->get("description", $event->description);
        $event->date = $request->get("date", $event->date);
        $event->time = $request->get("time", $event->time);
        $event->longitude = $request->get("longitude", $event->longitude);
        $event->latitude = $request->get("latitude", $event->latitude);
        $event->user_id = Auth::id();

        $event->save();
        
        return response()->json(['message' => 'Esdeveniment actualitzat correctament']);
    }

    //update user
    public function AdminUpdateUser(Request $request, $id)
    {
        try {
            $user = User::findOrFail($id); //Get the user
            
            $validated = $request->validate([ //Validate the request
                'name' => 'required|string|max:255', //Validate the name
                'email' => 'required|email|unique:users,email,' . $id, //Validate the email
                'role' => 'required|integer|in:0,1,2', //Validate the role
                'surname' => 'required|string|max:255', //Validate the surname
                'password' => $request->filled('password') ? 'required|min:8' : '', //Validate the password
            ]);

            $user->name = $validated['name']; //Set the name
            $user->surname = $validated['surname']; //Set the surname
            $user->email = $validated['email']; //Set the email
            $user->role = $validated['role']; //Set the role
            
            if ($request->filled('password')) { //If the password is filled
                $user->password = Hash::make($request->password); //Set the password
            }

            $user->save(); //Save the user

            return response()->json([ //Return the response
                'message' => 'Usuari actualitzat correctament', //Return the message
                'user' => $user //Return the user
            ]);
        } catch (\Exception $e) {
            return response()->json([ //Return the response
                'message' => 'Error al actualizar l\'usuari', //Return the message
                'error' => $e->getMessage() //Return the error
            ], 422); //Return the status code
        }
    }

    //delete product
    public function AdminDeleteProduct($id){
        $product = Product::find($id);
        $product->delete();
        return response()->json(['message' => 'Producte eliminat correctament']);
    }

    //delete user
    public function AdminDeleteUser($id)
    {
        $user = User::find($id); //Get the user
        $user->delete(); //Delete the user
        return response()->json(['message' => 'Usuari eliminat correctament']); //Return the message
    }

    //delete event
    public function AdminDeleteEvent($id){
        $event = Event::find($id);
        $event->delete();
        return response()->json(['message' => 'Esdeveniment eliminat correctament']);
    }

    //get all products
    public function getAllProductsAdmin()
    {
        try {
            $products = Product::all();
            return response()->json($products);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al cargar los productos: ' . $e->getMessage()], 500);
        }
    }

    //get all events
    public function getAllEventsAdmin()
    {
        $events = Event::all();
        return response()->json($events);
    }

    //get all users
    public function getAllUsersAdmin()
    {
        $users = User::all(); //Get all the users
        return response()->json($users); //Return the users
    }



    


}
