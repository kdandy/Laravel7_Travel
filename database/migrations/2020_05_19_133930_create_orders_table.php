<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('tour_id');
            $table->bigInteger('transaction_id');
            $table->string('via',16);
            $table->string('channel',16);
            $table->string('amount');
            $table->text('payment_no');
            $table->timestamp('expired');
            $table->timestamp('paymentTime')->nullable();
            $table->string('status');
            $table->string('hometown');
            $table->tinyInteger('qty');
            $table->timestamp('date_start')->nullable();
            $table->timestamp('date_end')->nullable();
            $table->tinyInteger('rating')->nullable();
            $table->text('testimonial')->nullable();
            $table->text('note')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('tour_id')->references('id')->on('tours')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
