<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

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
