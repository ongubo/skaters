<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Favourite extends Model
{
    protected $table = 'user_tricks';
    protected $fillable = [
        'trick_id', 'user_id',
    ];
}
