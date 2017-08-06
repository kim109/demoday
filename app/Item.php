<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $casts = [
        'event_open' => 'boolean'
    ];

    public function funds()
    {
        return $this->hasMany('App\Fund');
    }
}
