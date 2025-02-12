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
    protected $fillable = [ //Fillable attributes
        'name', //Name
        'category_id', //Category id
        'bid', //Bid
        'user_id', //User id
        'description', //Description
        'price', //Price
        'longitude', //Longitude
        'latitude', //Latitude
        'image', //Image
        'status' //Status
    ];
    
    public function user() //Function to get the user
    {
        return $this->belongsTo(User::class); //Return the user
    }

    public function category() //Function to get the category
    {
        return $this->belongsTo(Category::class); //Return the category
    }

    public function basket() //Function to get the basket
    {
        return $this->hasMany(Basket::class); //Return the basket
    }

    public function favorite() //Function to get the favorite
    {
        return $this->hasMany(Favorite::class); //Return the favorite
    }

    public function comments() //Function to get the comments
    {
        return $this->hasMany(Comment::class); //Return the comments
    }  

    public function auction() //Function to get the auction
    {
        return $this->hasOne(Auction::class); //Return the auction
    }
    
    public function review() //Function to get the review
    {
        return $this->hasOne(Review::class); //Return the review
    }

    public function transactions() //Function to get the transactions
    {
        return $this->hasOne(Transaction::class); //Return the transaction
    }
}


