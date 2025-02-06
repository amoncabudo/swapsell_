<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;
use Carbon\Carbon;

use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{



   
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

    public function addcomment(Request $request)
    {
        $id = Auth::user()->id;
        $product_id = $request->get("comment")["id_product"];
        $description = $request->get("comment")["message"];

        $comment = new Comment();
        $comment->product_id = $product_id;
        $comment->user_id = auth()->id();
        $comment->description = $description;



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

    public function update(Request $request, $id)
    {
        $comment = Comentario::find($id);

        if (!$comment) {
            return redirect()->route('Products')->with('error', 'Comentari no trobat');
        }

        $comment->description = $request->get("description");
        $comment->save();
        return redirect()->route('Products')->with('success', 'Comentari actualitzat correctament');
    }
}
