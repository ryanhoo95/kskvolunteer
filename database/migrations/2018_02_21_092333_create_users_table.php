<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('User', function (Blueprint $table) {
            $table->increments('userID');
            $table->string('email', 100)->unique();
            $table->string('password', 20);
            $table->rememberToken();
            $table->string('fullName', 100);
            $table->string('profileName', 100);
            $table->date('dateOfBirth');
            $table->char('gender', 1);
            $table->string('icPassport', 20)->unique();
            $table->string('address', 255);
            $table->string('phoneNo', 20);
            $table->string('profileImage', 255)->nullable();
            $table->integer('userType')->unsigned();
            $table->char('status', 1);
            $table->timestamps();

            //foreign key contraint
            $table->foreign('userType')->references('userTypeID')->on('UserType');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
