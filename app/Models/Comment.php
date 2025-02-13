<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory; //Use the factory
    protected $fillable = [ //Fillable attributes
        'id', //Id
        'product_id', //Product id
        'user_id', //User id
        'image', //Image
        'description', //Description
        'created_at', //Created at
        'updated_at', //Updated at
    ];
    protected $table = 'comentaries'; //Table name

    public function user() //Function to get the user
    {
        return $this->belongsTo(User::class); //Return the user
    }

    public function product() //Function to get the product
    {
        return $this->belongsTo(Product::class); //Return the product
    }


}
