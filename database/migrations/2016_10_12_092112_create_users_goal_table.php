<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersGoalTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users_goal', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->length(11);
            $table->tinyInteger('goals_id')->length(4);
            $table->decimal('goal_weight', 10, 2);
            $table->tinyInteger('weight_preferred_pace_id')->length(4);
            $table->tinyInteger('dietary_requirements_id')->length(4);
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
        Schema::dropIfExists('users_goal');
    }
}
