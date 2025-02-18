<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Auction extends Model //Class to manage the auctions
{
    protected $fillable = [ //Fillable attributes
        'product_id', //Product id
        'user_id', //User id
        'start_price', //Start price
        'current_price', //Current price
        'start_time', //Start time
        'end_time', //End time
        'status', //Status
        'last_bidder_id', //Last bidder id
        'image' //Image
    ];
    
    

    public function product() //Function to get the product
    {
        return $this->belongsTo(Product::class); //Return the product
    }

    public function lastBidder(){ //Function to get the last bidder
        return $this->belongsTo(User::class, 'last_bidder_id'); //Return the last bidder
    }
}
