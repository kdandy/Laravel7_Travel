<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $guarded = [];

    public function tour()
    {
        return $this->belongsTo('App\Tour');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
