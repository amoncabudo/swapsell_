<?php

namespace App\Models;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    public function user(): BelongsTo {
        return $this->belongsTo(User::class);
    }
}