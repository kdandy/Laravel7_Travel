<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    public $timestamps = false;
    protected $guarded = [];

    public function tours()
    {
        return $this->hasMany('App\Tour');
    }
}
