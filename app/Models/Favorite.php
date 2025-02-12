<?php

namespace App\Models;

use App\Model\Product;
use App\Model\User;
use Illuminate\Database\Eloquent\Model;

class Favorite extends Model
{
 
    protected $fillable = ['product_id', 'user_id']; //Fillable attributes

    public function product_id() //Function to get the product id
    {
        return $this->hasMany(Product::class); //Return the products
    }

    public function user() //Function to get the user
    {
        return $this->belongsTo(User::class); //Return the user
    }

    public static function addFavorite($productId, $userId) //Function to add a favorite
    {
        return self::create(['product_id' => $productId, 'user_id' => $userId]); //Create the favorite
    }
}