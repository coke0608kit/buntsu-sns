<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Rorecek\Ulid\HasUlid;

class Ticket extends Model
{
    use HasUlid;

    /**
     * IDが自動増分されるか
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * 自動増分IDの「タイプ」
     *
     * @var string
     */
    protected $keyType = 'string';
    protected $fillable = [
        'from', 'to', 'used', 'done', 'published'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo('App\User', 'to')->withDefault();
    }

    public function profile(): BelongsTo
    {
        return $this->belongsTo('App\Profile', 'to', 'user_id')->withDefault();
    }
}
