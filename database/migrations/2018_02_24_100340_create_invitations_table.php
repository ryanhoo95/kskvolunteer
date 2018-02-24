<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInvitationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invitation', function (Blueprint $table) {
            $table->increments('invitation_id');
            $table->integer('activity_id')->unsigned();
            $table->integer('invited_by')->unsigned();
            $table->integer('target_to')->unsigned();
            $table->string('invitation_code', 100);
            $table->char('status', 1);
            $table->timestamps();

            //foreign key contraint
            $table->foreign('activity_id')->references('activity_id')->on('activity');
            $table->foreign('invited_by')->references('user_id')->on('user');
            $table->foreign('target_to')->references('user_id')->on('user');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('invitation');
    }
}
