<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    public function funds()
    {
        return $this->hasMany('App\Fund');
    }
}
