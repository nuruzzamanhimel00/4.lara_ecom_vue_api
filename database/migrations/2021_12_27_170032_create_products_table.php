<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('userid')->unsigned();
            $table->integer('categoryId')->unsigned();
            $table->integer('brandId')->unsigned();
            $table->string('title');
            $table->text('description');
            $table->string('slug');
            $table->integer('quantity')->unsigned();
            $table->integer('price')->unsigned();
            $table->integer('offer_price')->unsigned()->nullable();
            $table->integer('productstatus_id')->unsigned();
            $table->string('ip_address');
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
        Schema::dropIfExists('products');
    }
}
