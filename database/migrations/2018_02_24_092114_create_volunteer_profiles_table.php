<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVolunteerProfilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('volunteer_profile', function (Blueprint $table) {
            $table->increments('volunteer_profile_id');
            $table->string('emergency_contact', 20);
            $table->string('emergency_name', 100);
            $table->string('emergency_relation', 100);
            $table->integer('user_id')->unsigned();
            $table->integer('occupation')->unsigned();
            $table->string('occupation_remark', 100)->nullable();
            $table->integer('medium')->unsigned();
            $table->string('medium_remark', 100)->nullable();
            $table->integer('total_volunteer_duration')->unsigned();
            $table->integer('blacklisted_number')->unsigned();
            $table->timestamps();

            //foreign key contraint
            $table->foreign('user_id')->references('user_id')->on('user');
            $table->foreign('occupation')->references('occupation_type_id')->on('occupation_type');
            $table->foreign('medium')->references('medium_type_id')->on('medium_type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('volunteer_profile');
    }
}
