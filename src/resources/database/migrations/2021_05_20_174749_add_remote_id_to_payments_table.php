<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddRemoteIdToPaymentsTable extends Migration
{
    public function up()
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->string('remote_id')->nullable()->after('hash');
        });

        Schema::table('payments', function (Blueprint $table) {
            $table->unique(['payment_method_id', 'remote_id'], 'unique_remote_id');
        });
    }

    public function down()
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropUnique(['payment_method_id', 'remote_id']);
        });

        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn('remote_id');
        });
    }
}
