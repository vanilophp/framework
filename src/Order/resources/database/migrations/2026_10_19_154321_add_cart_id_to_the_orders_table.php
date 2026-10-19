<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            if (!Schema::hasColumn('orders', 'cart_id')) {
                $table->bigInteger('cart_id')->unsigned()->nullable();
                // It is intentionally not a foreign key, because carts
                // are deleted when an order is placed. The scope of
                // this column is to prevent the duplicate orders
                // of a cart if checkout race condition occurs
                $table->unique('cart_id');
            }
        });
    }

    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropUnique(['cart_id']);
            $table->dropColumn('cart_id');
        });
    }
};
