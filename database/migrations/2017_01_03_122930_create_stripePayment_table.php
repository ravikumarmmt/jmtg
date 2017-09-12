<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStripePaymentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stripe_payment', function (Blueprint $table) {
            $table->increments('id');
            $table->text('card_no');
            $table->string('name', 255);
            $table->tinyInteger('last4')->length(4);
            $table->string('type', 24);
            $table->decimal('amount', 10, 2);
            $table->string('currency', 24);
            $table->string('result', 32);
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
        Schema::dropIfExists('stripe_payment');
    }
}
