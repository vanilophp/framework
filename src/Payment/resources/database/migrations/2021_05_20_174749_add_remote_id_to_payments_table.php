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
        // The extra index below is needed to be able to drop the `unique_remote_id` index
        // otherwise MySQL will use the `unique_remote_id` index in the foreign key and
        // throw a "needed in a foreign key constraint" error when trying to drop it
        Schema::table('payments', function (Blueprint $table) {
            $table->index('payment_method_id');
        });

        Schema::table('payments', function (Blueprint $table) {
            $table->dropUnique('unique_remote_id');
        });

        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn('remote_id');
        });
    }
//
//    private function isSqlite(): bool
//    {
//        return 'sqlite' === Schema::connection($this->getConnection())
//                ->getConnection()
//                ->getPdo()
//                ->getAttribute(PDO::ATTR_DRIVER_NAME)
//            ;
//    }
}
