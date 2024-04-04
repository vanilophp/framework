<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::table('channels', function (Blueprint $table) {
            $table->unsignedBigInteger('billing_zone_id')->nullable();
            $table->unsignedBigInteger('shipping_zone_id')->nullable();

            if (Schema::hasTable('zones')) {
                $table->foreign('billing_zone_id')->references('id')->on('zones');
                $table->foreign('shipping_zone_id')->references('id')->on('zones');
            }
        });

        Schema::table('channels', function (Blueprint $table) {
            $table->dropColumn('billing_countries');
        });
        Schema::table('channels', function (Blueprint $table) {
            $table->dropColumn('shipping_countries');
        });
    }

    public function down(): void
    {
        Schema::table('channels', function (Blueprint $table) {
            $table->json('billing_countries')->nullable();
            $table->json('shipping_countries')->nullable();
        });

        Schema::table('channels', function (Blueprint $table) {
            if (!$this->isSqlite() && Schema::hasIndex('channels', ['billing_zone_id'])) {
                $table->dropForeign(['billing_zone_id']);
            }
            $table->dropColumn('billing_zone_id');
        });

        Schema::table('channels', function (Blueprint $table) {
            if (!$this->isSqlite() && Schema::hasIndex('channels', ['shipping_zone_id'])) {
                $table->dropForeign(['shipping_zone_id']);
            }
            $table->dropColumn('shipping_zone_id');
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
