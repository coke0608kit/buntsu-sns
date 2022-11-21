<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Buying extends Model
{
    protected $fillable = [
        'item', 'quantity', 'totalPrice', 'done', 'plan'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo('App\User');
    }

    public function profile(): BelongsTo
    {
        return $this->belongsTo('App\Profile', 'user_id', 'user_id')->withDefault();
    }
}
