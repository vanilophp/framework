<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            if (!Schema::hasColumn('orders', 'payment_method_id')) {
                $table->bigInteger('payment_method_id')->unsigned()->nullable();
                $table->foreign('payment_method_id')->references('id')->on('payment_methods');
            }
        });
    }

    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            if (!$this->isSqlite()) {
                $table->dropForeign(['payment_method_id']);
            }

            $table->dropColumn('payment_method_id');
        });
    }

    private function isSqlite(): bool
    {
        return 'sqlite' === Schema::connection($this->getConnection())
                ->getConnection()
                ->getPdo()
                ->getAttribute(PDO::ATTR_DRIVER_NAME)
        ;
    }
};
