<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_details', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('order_id')->length(10);
            $table->integer('user_id')->length(10);
            $table->string('plan_type', 255);
            $table->decimal('amount', 10, 2);
            $table->decimal('balance_amount', 10, 2);
            $table->tinyinteger('validity')->length(5);
            $table->tinyinteger('active')->length(2);
            $table->timestamps();
            $table->foreign('order_id')->references('id')->on('order')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_details');
    }
}
