<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

return new class () extends Migration {
    public function up()
    {
        Schema::table('shipments', function (Blueprint $table) {
            $table->decimal('carrier_cost', 15, 4)->nullable()->after('configuration');
        });
    }

    public function down()
    {
        Schema::table('shipments', function (Blueprint $table) {
            $table->dropColumn('carrier_cost');
        });
    }
};
