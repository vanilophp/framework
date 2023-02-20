<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

return new class () extends Migration {
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('fulfillment_status')->default('unfulfilled')->after('status');
        });
    }

    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('fulfillment_status');
        });
    }
};
