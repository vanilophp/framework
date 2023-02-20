<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up()
    {
        Schema::create('shippable_dummy_orders', function (Blueprint $table) {
            $table->id();
            $table->decimal('weight', 15, 2)->nullable();
            $table->decimal('width', 15, 2)->nullable();
            $table->decimal('height', 15, 2)->nullable();
            $table->decimal('length', 15, 2)->nullable();
            $table->decimal('total', 15, 2)->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::drop('shippable_dummy_orders');
    }
};
