<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Referral extends Model
{
protected $fillable=['name,email,country,phone,status'];

public function user(){

return $this->belongsTo('App\User');

}

public function earnings(){
 return $this->hasMany('App\Earning');

}


}
