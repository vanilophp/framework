<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up()
    {
        Schema::table('master_product_variants', function (Blueprint $table) {
            $table->text('description')->nullable();
            $table->string('state')->nullable();
        });
    }

    public function down()
    {
        Schema::table('master_product_variants', function (Blueprint $table) {
            $table->dropColumn('description');
        });

        Schema::table('master_product_variants', function (Blueprint $table) {
            $table->dropColumn('state');
        });
    }
};
