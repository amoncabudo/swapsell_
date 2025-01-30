<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Auction extends Model
{
    protected $fillable = [
        'product_id',
        'start_price',
        'current_price',
        'start_time',
        'end_time',
        'status'
    ];
    
    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}