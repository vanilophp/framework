<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTestProductsTable extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('test_products')) {
            Schema::create('test_products', function (Blueprint $table) {
                $table->increments('id');
                $table->string('sku')->nullable();
                $table->string('name');
                $table->decimal('price', 15, 2)->default(99);
                $table->timestamps();
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('test_products');
    }
}
