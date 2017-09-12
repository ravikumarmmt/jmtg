<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersProfileTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users_profile', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->length(11)->unsigned();
            $table->string('picture', 255);
            $table->string('gender', 8);
            $table->date('birthday');
            $table->decimal('height', 10, 0);
            $table->decimal('weight', 10, 0);
            $table->integer('activity_level')->length(11)->unsigned();
            $table->integer('exercise_days')->length(11)->unsigned();
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users_profile');
    }
}
