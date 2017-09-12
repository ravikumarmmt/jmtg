<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIngredientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ingredients', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->length(11);
            $table->string('name', 255);
            $table->float('calories', 8, 2);
            $table->float('protein', 8, 2);
            $table->float('carbs', 8, 2);
            $table->float('fats', 8, 2);
            $table->float('fiber', 8, 2);
            $table->float('serving', 8, 2);
            $table->string('serving_kind', 128);
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
        Schema::dropIfExists('ingredients');
    }
}
