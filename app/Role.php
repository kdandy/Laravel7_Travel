<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    public $timestamps = false;
    protected $guarded = [];

    public function users()
    {
        return $this->hasMany('App\User');
    }
}
