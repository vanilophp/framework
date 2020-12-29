<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUniqueIndexToOrderNumbers extends Migration
{
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->unique('number', 'order_number_unique');
        });
    }

    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropUnique('order_number_unique');
        });
    }
}
