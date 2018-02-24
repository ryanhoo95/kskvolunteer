<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateParticipationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('participation', function (Blueprint $table) {
            $table->increments('participation_id');
            $table->integer('activity_id')->unsigned();
            $table->integer('participant_id')->unsigned()->nullable();
            $table->string('participant_name', 100)->nullable();
            $table->string('participant_remark', 500)->nullable();
            $table->integer('participant_added_by')->unsigned()->nullable();
            $table->string('invitation_code', 100);
            $table->char('status', 1);
            $table->timestamps();

            //foreign key contraint
            $table->foreign('participant_id')->references('user_id')->on('user');
            $table->foreign('participant_added_by')->references('user_id')->on('user');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('participation');
    }
}
