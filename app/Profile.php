<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'icon', 'gender', 'year', 'month', 'day', 'zipcode1', 'zipcode2', 'pref', 'address1', 'address2', 'realname', 'profile', 'canSendGender', 'status', 'condition'
    ];

    public function user()
    {
        return $this->hasOne('App\User');
    }
}
