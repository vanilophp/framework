<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up()
    {
        Schema::table('shipping_methods', function (Blueprint $table) {
            $table->unsignedBigInteger('zone_id')->nullable()->after('carrier_id');
            $table->string('calculator')->nullable()->after('carrier_id');
        });
    }

    public function down()
    {
        Schema::table('shipping_methods', function (Blueprint $table) {
            $table->dropColumn('zone_id');
            $table->dropColumn('calculator');
        });
    }
};
