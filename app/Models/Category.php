<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = ['name']; //Fillable attributes
    
    public function products() //Function to get the products
    {
        return $this->hasMany(Product::class); //Return the products
    }
}
