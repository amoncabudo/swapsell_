<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;

use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{



   


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
        $comments = Comment::where('product_id', $product_id)->with("user")->orderBy('created_at', 'desc')->get();
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
