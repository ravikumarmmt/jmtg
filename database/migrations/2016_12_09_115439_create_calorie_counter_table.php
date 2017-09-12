<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCalorieCounterTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('calorie_counter', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->length(11)->unsigned();
            $table->enum('mealtype', ['breakfast', 'snacks', 'lunch', 'snack', 'dinner', 'drinks']);
            $table->string('mealid', 255)->nullable();
            $table->string('mealname');
            $table->longText('mealdata');
            $table->date('date');
            $table->timestamps();
        });
        // Here we create the FULLTEXT index
        DB::statement('ALTER TABLE calorie_counter ADD FULLTEXT search(mealname)');        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('calorie_counter', function($table) {
            $table->dropIndex('search');
        });
        
        Schema::dropIfExists('calorie_counter');
    }
}
