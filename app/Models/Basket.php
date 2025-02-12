<?php

namespace App\Models;

use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class Basket extends Model
{
    protected $fillable = ['product_id', 'user_id']; //Fillable attributes

    public function product() //Function to get the product
    {
        return $this->belongsTo(Product::class); //Return the product
    }

    public function user()
    {
        return $this->belongsTo(User::class); //Return the user
    }

    public static function addBasket($productId, $userId) //Function to add a product to the basket
    {
        return self::create(['product_id' => $productId, 'user_id' => $userId]); //Create the basket
    }
}