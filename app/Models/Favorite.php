<?php

namespace App\Models;

use App\Model\Product;
use App\Model\User;
use Illuminate\Database\Eloquent\Model;

class Favorite extends Model
{

    protected $fillable = ['product_id', 'user_id'];

    public function product_id()
    {
        return $this->hasMany(Product::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function addFavorite($productId, $userId)
    {
        return self::create(['product_id' => $productId, 'user_id' => $userId]);
    }
}