<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Winner extends Model
{
    protected $table = 'winner'; //Table name
    
    protected $fillable = [ //Fillable attributes
        'product_id', 
        'user_id', 
        'amount', 
        'winner_date' 
    ];

    public function user() //Function to get the user
    {
        return $this->belongsTo(User::class); //Return the user
    }

    public function product() //Function to get the product
    {
        return $this->belongsTo(Product::class); //Return the product
    }
} 