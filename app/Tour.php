<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tour extends Model
{
    protected $guarded = [];

    public function galleries()
    {
        return $this->hasMany('App\Gallery');
    }

    public function orders()
    {
        return $this->hasMany('App\Order');
    }

    public function package()
    {
        return $this->belongsTo('App\Package');
    }
}
