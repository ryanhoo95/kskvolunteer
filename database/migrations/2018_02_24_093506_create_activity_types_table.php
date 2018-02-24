<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateActivityTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('activity_type', function (Blueprint $table) {
            $table->increments('activity_type_id');
            $table->string('activity_title', 100);
            $table->string('description', 1000)->nullable();
            $table->string('remark', 1000)->nullable();
            $table->time('start_time');
            $table->time('end_time');
            $table->char('status', 1);
            $table->integer('created_by')->unsigned();
            $table->integer('updated_by')->unsigned();
            $table->timestamps();

            //foreign key contraint
            $table->foreign('created_by')->references('user_id')->on('user');
            $table->foreign('updated_by')->references('user_id')->on('user');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('activity_type');
    }
}
