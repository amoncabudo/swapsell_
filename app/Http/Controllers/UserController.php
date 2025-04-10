<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User; // Asegúrate de tener el modelo User
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function addUser(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'surname' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $user = new User([
            'name' => $request->name,
            'surname' => $request->surname,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('public/users');
            $user->image = 'storage/users/' . basename($imagePath);
        }

        $user->save();

        return redirect()->back()->with('success', 'Usuario creado correctamente');
    }
    
    public function getAllUsers()
    {
        $users = User::all(); //Get all the users
        return response()->json($users); //Return the users
    }

    public function destroy($id)
    {
        $user = User::find($id); //Get the user
        $user->delete(); //Delete the user
        return response()->json(['message' => 'Usuari eliminat correctament']); //Return the message
    }

    public function update(Request $request, $id)
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
}
