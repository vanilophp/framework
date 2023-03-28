<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up()
    {
        Schema::table('shipments', function (Blueprint $table) {
            $table->decimal('carrier_cost', 15, 4)->nullable()->after('configuration');
            $table->string('label_url', 1024)->nullable()->after('configuration');
            $table->longText('label_base64')->nullable()->after('configuration');
        });
    }

    public function down()
    {
        Schema::table('shipments', function (Blueprint $table) {
            $table->dropColumn('carrier_cost');
        });
        Schema::table('shipments', function (Blueprint $table) {
            $table->dropColumn('label_url');
        });
        Schema::table('shipments', function (Blueprint $table) {
            $table->dropColumn('label_base64');
        });
    }
};
