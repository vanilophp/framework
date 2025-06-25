<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up()
    {
        Schema::table('shipping_methods', function (Blueprint $table) {
            $table->unsignedBigInteger('shipping_category_id')->nullable();
            $table->string('shipping_category_matching_condition')->nullable()->after('carrier_id');

            if (!$this->isSqlite()) {
                $table->foreign('shipping_category_id')->references('id')->on('shipping_categories');
            }
        });
    }

    public function down()
    {
        Schema::table('shipping_methods', function (Blueprint $table) {
            if (!$this->isSqlite()) {
                $table->dropForeign(['shipping_category_id']);
            }
        });

        Schema::table('shipping_methods', function (Blueprint $table) {
            $table->dropColumn('shipping_category_id');
            $table->dropColumn('shipping_category_matching_condition');
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
