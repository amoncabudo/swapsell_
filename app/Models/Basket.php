<?php

namespace App\Models;
use App\Model\Product;
use App\Model\User;
use Illuminate\Database\Eloquent\Model;

class Basket extends Model
{
    public function product_id()
    {
        return $this->hasMany(Product::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}