<?php
namespace App\Models;
use App\Models\User;
use App\Models\Product;

use App\Models\Category;
use App\Models\Favorite;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $fillable = [ //Fillable attributes
        'user_id', //User id
        'product_id', //Product id
        'rating', //Rating
        'comment' //Comment
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