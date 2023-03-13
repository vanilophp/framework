<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            if (!Schema::hasColumn('orders', 'customer_id')) {
                $table->intOrBigIntBasedOnRelated('customer_id', Schema::connection(null), 'customers.id')->unsigned()->nullable()->after('billpayer_id');
                $table->foreign('customer_id')->references('id')->on('customers');
            }
            $table->unsignedBigInteger('shipping_method_id')->nullable()->after('shipping_address_id');

            $table->foreign('shipping_method_id')->references('id')->on('shipping_methods');
        });
    }

    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            if (!$this->isSqlite()) {
                $table->dropForeign(['shipping_method_id']);
            }

            $table->dropColumn('shipping_method_id');
        });
        Schema::table('orders', function (Blueprint $table) {
            if (!$this->isSqlite()) {
                $table->dropForeign(['customer_id']);
            }
            $table->dropColumn('customer_id');
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
