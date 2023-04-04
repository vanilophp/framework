<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->bigInteger('tax_category_id')->unsigned()->nullable();
        });
        Schema::table('master_products', function (Blueprint $table) {
            $table->bigInteger('tax_category_id')->unsigned()->nullable();
        });
        Schema::table('master_product_variants', function (Blueprint $table) {
            $table->bigInteger('tax_category_id')->unsigned()->nullable();
        });
    }

    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('tax_category_id');
        });
        Schema::table('master_products', function (Blueprint $table) {
            $table->dropColumn('tax_category_id');
        });
        Schema::table('master_product_variants', function (Blueprint $table) {
            $table->dropColumn('tax_category_id');
        });
    }
};
