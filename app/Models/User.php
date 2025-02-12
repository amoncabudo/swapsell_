<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
Use App\Models\Review;


class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [ //Fillable attributes
        'name',
        'surname',
        'email',
        'password',
        'role',
        'image'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [ //Hidden attributes
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array //Function to cast the attributes
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    public function comments() //Function to get the comments
    {
        return $this->hasMany(Comment::class); //Return the comments
    }

    public function products(){ //Function to get the products
        return $this->hasMany(Product::class); //Return the products

    }
        public function events() { //Function to get the events
    {
        return $this->hasMany(Event::class); //Return the events
    }
}
    public function basket(){ //Function to get the basket
        return $this->belongsTo(Basket::class); //Return the basket
    }

    public function review(){ //Function to get the review
        return $this->belongsTo(Review::class); //Return the review
    }

    public function transactions() //Function to get the transactions
    {
        return $this->hasMany(Transaction::class); //Return the transactions
    }
}
