<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Trick extends Model
{
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function places()
    {
        return $this->hasMany('App\TrickPlace');
    }
}
