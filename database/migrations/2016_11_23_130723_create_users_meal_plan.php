<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersMealPlan extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users_meal_plan', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->length(11)->unsigned();
            $table->longText('mealplan');
            $table->tinyInteger('plan_type')->length(4)->unsigned();
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
        Schema::dropIfExists('users_meal_plan');
    }
}
