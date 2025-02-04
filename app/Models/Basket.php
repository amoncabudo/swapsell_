<?php

namespace App\Models;

use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class Basket extends Model
{
    protected $fillable = ['product_id', 'user_id'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function addBasket($productId, $userId)
    {
        return self::create(['product_id' => $productId, 'user_id' => $userId]);
    }
}