<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Orders extends Model
{

    //
	
	protected $fillable =
	[
		'email',
		'topic',
		'subject',
		'pages',
		'style',
		'document',
		'academiclevel',
		 'langstyle',
		  'urgency',
		  'spacing',
		  'total',
		  'description',
		  'status',
		  'payment',
	
	
	];
}
