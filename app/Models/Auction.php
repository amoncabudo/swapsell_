<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Auction extends Model
{
    protected $fillable = [
        'product_id',
        'user_id',
        'start_price',
        'current_price',
        'start_time',
        'end_time',
        'status',
        'last_bidder_id'
    ];
    
    

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function lastBidder()
{
    return $this->belongsTo(User::class, 'last_bidder_id');
}
}