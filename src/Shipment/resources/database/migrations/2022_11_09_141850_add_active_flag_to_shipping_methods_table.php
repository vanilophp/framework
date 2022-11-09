<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

return new class () extends Migration {
    public function up()
    {
        Schema::table('shipping_methods', function (Blueprint $table) {
            $table->boolean('is_active')->default(true)->after('configuration');
        });
    }

    public function down()
    {
        Schema::table('shipping_methods', function (Blueprint $table) {
            $table->dropColumn('is_active');
        });
    }
};
