<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderMaterial extends Model
{
   protected $fillable=[
 'order_id',
 'filename',
 'original_filename',
 'email',
   ];
   
}
