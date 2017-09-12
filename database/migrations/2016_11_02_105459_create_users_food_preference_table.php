<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersFoodPreferenceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users_food_preference', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->length(11)->unsigned();
            $table->mediumInteger('category_id')->length(5)->unsigned();
            $table->mediumInteger('subcategory_id')->length(5)->unsigned();
            $table->tinyInteger('checked')->length(2)->unsigned()->default(0);
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
        Schema::dropIfExists('users_food_preference');
    }
}
