<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up()
    {
        Schema::table('shipping_methods', function (Blueprint $table) {
            $table->integer('usage_count')->unsigned()->default(0);
            $table->dateTime('last_usage_at')->nullable();
        });
    }

    public function down()
    {
        Schema::table('shipping_methods', function (Blueprint $table) {
            $table->dropColumn(['usage_count', 'last_usage_at']);
        });
    }
};
