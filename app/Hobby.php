<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Hobby extends Model
{
    protected $fillable = [
        'name',
    ];

    public function getHashtagAttribute(): string
    {
        return '#' . $this->name;
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany('App\User')->withTimestamps();
    }
}
