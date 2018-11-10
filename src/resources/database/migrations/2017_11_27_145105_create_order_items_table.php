<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderItemsTable extends Migration
{
    public function up()
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('order_id')->unsigned();
            $table->string('product_type');
            $table->integer('product_id')->unsigned();
            $table->string('name')->nullable()->comment('The product name at the moment of buying');
            $table->integer('quantity');
            $table->decimal('price', 15, 4);
            $table->timestamps();

            $table->foreign('order_id')
                ->references('id')
                ->on('orders')
                ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::drop('order_items');
    }
}
