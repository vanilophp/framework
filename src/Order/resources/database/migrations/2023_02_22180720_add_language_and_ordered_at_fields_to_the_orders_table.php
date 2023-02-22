<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->char('language', 2)->nullable()->after('fulfillment_status')->comment('The two letter ISO 639-1 language code');
            $table->timestamp('ordered_at')->nullable()->after('notes');
        });

        // Set default value for `ordered_at` on existing records
        DB::update('UPDATE orders set ordered_at = created_at');
    }

    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('language');
        });
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('ordered_at');
        });
    }
};
