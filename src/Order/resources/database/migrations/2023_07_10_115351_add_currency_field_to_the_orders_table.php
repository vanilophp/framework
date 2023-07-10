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
            $table->char('currency', 3)->nullable()->after('language');
        });

        // Set default value for `currency` on existing records
        $currencyInConfig = config('vanilo.foundation.currency.code');
        if (is_string($currencyInConfig) && 3 === strlen($currencyInConfig)) {
            DB::update('UPDATE orders set currency = ?', [$currencyInConfig]);
        }
    }

    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('currency');
        });
    }
};
