<?php

namespace App\Models;
use App\Models\User;
use App\Models\Category;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Obtiene la categoría del producto.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Obtiene los comentarios del producto.
     */
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
}
