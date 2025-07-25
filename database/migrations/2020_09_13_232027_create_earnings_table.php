<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEarningsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('earnings', function (Blueprint $table) {
            $table->increments('id');
             $table->text('details');
             $table ->double('paid_in',8,2);
             $table->double('paid_out',8,2);
             $table->unsignedInteger('referral_id')->index();
             $table->foreign('referral_id')->references('id')->on('referrals');
             $table->unsignedInteger('user_id')->index();
             $table->foreign('user_id')->references('id')->on('users');
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
        Schema::dropIfExists('earnings');
    }
}
