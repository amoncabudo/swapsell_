<?php

namespace App\Models;
use App\Models\User;
use App\Models\Category;
use App\Models\Favorite;
use App\Model\Basket;
use App\Models\Comment;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = ['name', 'category_id', 'bid']; // Asegúrate de incluir 'category_id' si lo usas en tu base de datos.
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Obtiene la categoría del producto.
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function basket()
    {
        return $this->belongsTo(Basket::class);
    }

    public function favorite()
    {
        return $this->belongsTo(Favorite::class);
    }

    /**
     * Obtiene los comentarios del producto.
     */
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }  

    public function auction(){
        return $this->hasOne(Auction::class);
    }
}


