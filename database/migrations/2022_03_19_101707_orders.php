<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function(Blueprint $b) {
            $b->id();
            $b->dateTime('order_date');
            $b->unsignedBigInteger('product_id');
            $b->unsignedBigInteger('customer_id');
            $b->integer('quantity');
            $b->double('price');
            $b->foreign('product_id')->references('id')->on('products');
            $b->foreign('customer_id')->references('id')->on('customers');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('orders');
    }
};
