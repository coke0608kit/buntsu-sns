<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class First extends Model
{
    protected $fillable = [
        'user_id','done',
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
