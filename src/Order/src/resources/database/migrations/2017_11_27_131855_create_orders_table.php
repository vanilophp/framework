<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateOrdersTable extends Migration
{
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->increments('id');
            $table->string('number', 32);
            $table->string('status', 32);
            $table->intOrBigIntBasedOnRelated('user_id', Schema::connection(null), 'users.id')->nullable();
            $table->integer('billpayer_id')->unsigned()->nullable();
            $table->integer('shipping_address_id')->unsigned()->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('user_id')
                ->references('id')
                ->on('users');

            $table->foreign('billpayer_id')
                  ->references('id')
                  ->on('billpayers');

            $table->foreign('shipping_address_id')
                  ->references('id')
                  ->on('addresses');
        });
    }

    public function down()
    {
        Schema::drop('orders');
    }
}
