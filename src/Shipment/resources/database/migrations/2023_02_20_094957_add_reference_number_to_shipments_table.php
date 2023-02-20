<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

return new class () extends Migration {
    public function up()
    {
        Schema::table('shipments', function (Blueprint $table) {
            $table->string('reference_number')->nullable()->after('tracking_number');
        });
    }

    public function down()
    {
        Schema::table('shipments', function (Blueprint $table) {
            $table->dropColumn('reference_number');
        });
    }
};
