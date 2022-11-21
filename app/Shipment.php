<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Shipment extends Model
{
    protected $fillable = [
        'user_id', 'plan', 'year', 'month', 'term', 'shipment'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo('App\User')->withDefault();
    }

    public function profile(): BelongsTo
    {
        return $this->belongsTo('App\Profile', 'user_id', 'user_id')->withDefault();
    }
}
