<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    public $timestamps = false;
    protected $guarded = [];

    public function galleries()
    {
        return $this->hasMany('App\Gallery');
    }
}
