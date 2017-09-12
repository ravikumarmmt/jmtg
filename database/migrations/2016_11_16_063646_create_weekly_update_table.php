<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWeeklyUpdateTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('weekly_update', function (Blueprint $table) {
            $table->increments('id');
            $table->mediumInteger('user_id');
            $table->decimal('weight', 10, 2);
            $table->decimal('arm', 10, 2);
            $table->decimal('waist', 10, 2);
            $table->decimal('hips', 10, 2);
            $table->decimal('thighs', 10, 2);
            $table->decimal('adherence', 10, 2);
            $table->decimal('menstrual_cycle', 10, 2);
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
        Schema::dropIfExists('weekly_update');
    }
}
