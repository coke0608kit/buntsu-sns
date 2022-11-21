<?php

namespace App;

use App\Mail\BareMail;
use App\Notifications\PasswordResetNotification;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Rorecek\Ulid\HasUlid;

class User extends Authenticatable
{
    use HasUlid;
    use Notifiable;

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

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'delivery_id', 'name', 'nickname', 'email', 'password', 'payjp_customer_id','plan','official','planStatus', 'subId'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new PasswordResetNotification($token, new BareMail()));
    }

    public function profile(): HasOne
    {
        return $this->hasOne('App\Profile');
    }

    public function firsts(): HasMany
    {
        return $this->hasMany('App\First');
    }

    public function articles(): HasMany
    {
        return $this->hasMany('App\Article');
    }

    public function followers(): BelongsToMany
    {
        return $this->belongsToMany('App\User', 'follows', 'followee_id', 'follower_id')->withTimestamps();
    }

    public function followings(): BelongsToMany
    {
        return $this->belongsToMany('App\User', 'follows', 'follower_id', 'followee_id')->withTimestamps();
    }

    public function likes(): BelongsToMany
    {
        return $this->belongsToMany('App\Article', 'likes')->withTimestamps();
    }

    public function isFollowedBy(?User $user): bool
    {
        return $user
            ? (bool)$this->followers->where('id', $user->id)->count()
            : false;
    }

    public function getCountFollowersAttribute(): int
    {
        return $this->followers->count();
    }

    public function getCountFollowingsAttribute(): int
    {
        return $this->followings->count();
    }

    public function hobbies(): BelongsToMany
    {
        return $this->belongsToMany('App\Hobby')->withTimestamps();
    }

    public function blockers(): BelongsToMany
    {
        return $this->belongsToMany('App\User', 'blocks', 'blockee_id', 'blocker_id')->withTimestamps();
    }

    public function blockings(): BelongsToMany
    {
        return $this->belongsToMany('App\User', 'blocks', 'blocker_id', 'blockee_id')->withTimestamps();
    }

    public function isBlockedBy(?User $user): bool
    {
        return $user
            ? (bool)$this->blockers->where('id', $user->id)->count()
            : false;
    }

    public function purchase(): HasMany
    {
        return $this->hasMany('App\Buying')->orderBy('created_at', 'DESC');
    }

    public function shipments(): HasMany
    {
        return $this->hasMany('App\Shipment');
    }
}
