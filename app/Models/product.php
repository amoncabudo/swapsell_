<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class product extends Model
{
    public function user(): BelongsTo {
        return $this->belongsTo(User::class);
    }
}
