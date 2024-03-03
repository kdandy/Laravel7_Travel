<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Gallery extends Model
{
    public $timestamps = false;
    protected $guarded = [];

    public function tour()
    {
        return $this->belongsTo('App\Tour');
    }

    public function company()
    {
        return $this->belongsTo('App\Company');
    }

}
