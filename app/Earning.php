<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Earning extends Model
{
 protected $fillable=['content'];

public function user(){

    return $this->belongsTo('App\User');
}

public function referral(){

    return $this->belongsTo('App\Referral');
}

}
