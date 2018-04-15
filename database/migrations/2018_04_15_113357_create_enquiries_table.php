<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEnquiriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('enquiry', function (Blueprint $table) {
            $table->increments('enquiry_id');
            $table->integer('activity_id')->unsigned();
            $table->integer('user_id')->unsigned();
            $table->char('status', 1);
            $table->timestamps();

            //foreign key contraint
            $table->foreign('activity_id')->references('activity_id')->on('activity');
            $table->foreign('user_id')->references('user_id')->on('user');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('enquiries');
    }
}
