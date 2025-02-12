<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Transaction extends Model
{
    protected $table = 'transactions'; //Table name 

    protected $fillable = [ //Fillable attributes
        'user_id', 
        'buyer_id', 
        'category_id', 
        'product_id', 
        'name', 
        'description', 
        'price', 
        'longitude', 
        'latitude', 
        'image', 
        'status', 
        'bid' 
    ];

    public function seller() //Function to get the seller
    {
        return $this->belongsTo(User::class, 'user_id'); //Return the seller
    }

    public function buyer() //Function to get the buyer
    {
        return $this->belongsTo(User::class, 'buyer_id'); //Return the buyer
    }

    public function category() //Function to get the category
    {
        return $this->belongsTo(Category::class); //Return the category
    }

    public function product() //Function to get the product
    {
        return $this->belongsTo(Product::class); //Return the product
    }

    public function user() //Function to get the user
    {
        return $this->belongsTo(User::class); //Return the user
    }
}
