<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddDimensionsToProductsTable extends Migration
{
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->decimal('weight', 15, 4)->nullable()->after('state');
            $table->decimal('height', 15, 4)->nullable()->after('state');
            $table->decimal('width', 15, 4)->nullable()->after('state');
            $table->decimal('length', 15, 4)->nullable()->after('state');
        });
    }

    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('weight');
        });
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('height');
        });
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('width');
        });
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('length');
        });
    }
}
