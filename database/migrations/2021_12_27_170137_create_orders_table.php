<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

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
            $table->increments('id');
            $table->unsignedInteger('userid');
            $table->unsignedInteger('paymentid');
            $table->string('ip_address')->nullable();
            $table->string('name');
            $table->string('phone_number');
            $table->text('shipping_address');
            $table->text('email');
            $table->text('message')->nullable();
            $table->unsignedInteger('is_paid');
            $table->unsignedInteger('is_complete');
            $table->unsignedInteger('is_seen_by_admin');
            $table->string('transation_id');
            $table->integer('shipping_cost');
            $table->integer('customer_discount');
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
        Schema::dropIfExists('orders');
    }
}
