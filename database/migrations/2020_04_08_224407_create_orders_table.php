<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->increments('id');
			$table->string('email');
	       $table->text('topic');
		   	$table->string('subject');
			   	$table->string('pages');
				   	$table->string('style');
			
				   	$table->string('document');
				   	$table->string('academiclevel');
					
					   	$table->string('langstyle');
				   	$table->string('urgency');
					
					   	$table->string('spacing');
				   	$table->string('total');
					   $table->text('description');
					   
					      	$table->string('status');
						$table->string('payment');
		   
			
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
