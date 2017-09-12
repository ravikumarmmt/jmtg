<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMtgPlanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mtg_plan', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 16);
            $table->string('details', 120);
            $table->decimal('amount_per_week', 10,2);
            $table->decimal('amount', 10,2);
            $table->tinyInteger('level')->length(2);
            $table->tinyInteger('validity')->length(4);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mtg_plan');
    }
}
