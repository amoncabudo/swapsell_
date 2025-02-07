<?php

namespace App\Models;
use App\Models\User;
use App\Models\Category;
use App\Models\Favorite;
use App\Model\Basket;
use App\Models\Comment;
use Illuminate\Database\Eloquent\Model;
use App\Models\Product;
use App\Models\Review;

class Product extends Model
{
    protected $fillable = [
        'name',
        'category_id',
        'bid',
        'user_id',
        'description',
        'price',
        'longitude',
        'latitude',
        'image',
        'status'
    ];
    
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
        return $this->hasMany(Basket::class);
    }

    public function favorite()
    {
        return $this->hasMany(Favorite::class);
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
    
    public function review()
    {
        return $this->hasOne(Review::class);
    }

    public function transactions()
    {
        return $this->hasOne(Transaction::class);
    }
}


