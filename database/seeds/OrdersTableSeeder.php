<?php

use Illuminate\Database\Seeder;

class OrdersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
	Orders::truncate();
	$faker= \Faker\Factory::create();
	
	
	    Orders::create([
     
            'email' => 'ericmutua3462@gmail.com',
            'topic' => 'Evolution of Business Presentation',
			  'subject'=>'Business',
				'pages'=>'3',
					'style'=>'APA',
					'document'=>'PowerPoint Presentation ',
					 'academiclevel'=>'High School',
					 	'langstyle'	=>'English (U.S.)',
						'urgency'=>'7 days',
						'spacing'=>'DOUBLE',
							'total'=>'40.15',
								'description'=>'Resource:  Your Week 2 collaborative discussion and the Ch. 2 of Introduction to Business
 
Research the evolution of business with your assigned team members.
 
Locate information on the following points:
 
Feudalism
Mercantilism
Capitalism
Commerce
Property rights
The Industrial Revolution
 
Individually, create a 10- to 15-slide Microsoft® PowerPoint® presentation describing the evolution of business.
 
Provide examples and appropriate visuals to illustrate each phase of business.
 
Include detailed speaker notes, a title slide, and an APA-formatted reference slide.',
'status'=>'Pending payment',
'payment'=>'pending',

						
					
					
        ]);
	
	
	for ($i=0;$i<50; $i++){
	  orders::create([
	    'email'=>$faker->name,
	   'topic'=>$faker->topic,
	  'subject'=>$faker-> subject,
	'pages'=>$faker->pages,
	'style'=>$faker->style,
	'document'=>$faker->document,
 'academiclevel'=>$faker->academiclevel ,
	'langstyle'	=>$faker-> langstyle,
		'urgency'=>$faker->urgency,		  
			'spacing'=>$faker->spacing,
				
			'total'=>$faker->total ,
					
				'description'=>$faker->description,

 
'status'=>$faker->status,

'payment'=>$faker->payment,			
					
	  
	  ]);
	
		
					
					 
	  
	  
	  
	  
	  
	  
	  }
	
        //
    }
}
