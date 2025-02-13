<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;
use Carbon\Carbon;
use App\Models\User;

use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    //Calculate the time elapsed
    private function calcularTiempoTranscurrido($fecha)
{
    $fecha = Carbon::parse($fecha); //Parse the date
    $ahora = Carbon::now(); //Get the current date
    
    $diferencia = (int)$fecha->diffInSeconds($ahora); //Get the difference in seconds
    
    if ($diferencia < 60) { //If the difference is less than 60 seconds
        return "Hace " . $diferencia . " segundo" . ($diferencia == 1 ? '' : 's'); //Return the time elapsed
    }
    
    $diferencia = (int)$fecha->diffInMinutes($ahora); //Get the difference in minutes
    if ($diferencia < 60) { //If the difference is less than 60 minutes
        return "Hace " . $diferencia . " minuto" . ($diferencia == 1 ? '' : 's'); //Return the time elapsed
    }
    
    $diferencia = (int)$fecha->diffInHours($ahora); //Get the difference in hours
    if ($diferencia < 24) { //If the difference is less than 24 hours
        return "Hace " . $diferencia . " hora" . ($diferencia == 1 ? '' : 's'); //Return the time elapsed
    }
    
    $diferencia = (int)$fecha->diffInDays($ahora); //Get the difference in days
    return "Hace " . $diferencia . " día" . ($diferencia == 1 ? '' : 's'); //Return the time elapsed
}

    //Add a comment
    public function addcomment(Request $request) 
    {

        $request->validate([
            'comment.id_product' => 'required|integer|exists:products,id',
            'comment.message' => 'required|string|max:1000',
            
        ]);

        $id = Auth::user()->id; //Get the user id
        $product_id = $request->get("comment")["id_product"]; //Get the product id
        $description = $request->get("comment")["message"]; //Get the description
        $image = Auth::user()->image;

        $imagePath = null;
        if ($image) {
            $imagePath = $image->store('comment_images', 'public'); // Guardar la imagen en el disco público
        }

        $comment = new Comment(); //Create a new comment
        $comment->product_id = $product_id; //Set the product id
        $comment->user_id = auth()->id(); //Set the user id
        $comment->description = $description; //Set the description
        $comment->image = $image; //Set the image




        $comment->save();
        $comments = Comment::where('product_id', $product_id)
        ->with("user")
        ->orderBy('created_at', 'desc')
        ->get()
        ->map(function ($comment) {
            $comment->tiempo_transcurrido = $this->calcularTiempoTranscurrido($comment->created_at);
            return $comment;
        });

        
        return $comments;
    }

    public function showUserImage($image)
    {
        return url('storage/' . $image); // Genera la URL pública para la imagen
    }
    
    public function show($id)
    {
        $comment = Comentario::find($id);
        return Inertia::render('Comment', ['comment' => $comment]);
    }

    public function delete($id)
    {
        $comment = Comentario::find($id);
        $comment->delete();
        return redirect()->route('Products')->with('success', 'Comentari eliminat correctament');
    }

    // public function update(Request $request, $id)
    // {
    //     $comment = Comentario::find($id);

    //     if (!$comment) {
    //         return redirect()->route('Products')->with('error', 'Comentari no trobat');
    //     }

    //     $comment->description = $request->get("description");
    //     $comment->save();
    //     return redirect()->route('Products')->with('success', 'Comentari actualitzat correctament');
    // }
}
