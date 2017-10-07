<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Konekt\Product\Models\ProductStateProxy;

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
            $table->increments('id');
            $table->string('name');
            $table->string('slug')->nullable();
            $table->string('sku');
            $table->text('excerpt')->nullable();
            $table->text('description')->nullable();
            $table->enum('state', ProductStateProxy::values())->default(ProductStateProxy::defaultValue());
            $table->text('meta_keywords')->nullable();
            $table->text('meta_description')->nullable();
            $table->softDeletes();
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
        Schema::drop('products');
    }
}
