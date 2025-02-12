<?php

namespace App\Models;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    public function user() //Function to get the user
    {
        return $this->belongsTo(User::class); //Return the user
    }
}