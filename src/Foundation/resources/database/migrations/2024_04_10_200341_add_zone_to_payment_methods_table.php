<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::table('payment_methods', function (Blueprint $table) {
            $table->unsignedBigInteger('zone_id')->nullable()->after('name');

            if (Schema::hasTable('zones')) {
                $table->foreign('zone_id')->references('id')->on('zones');
            }
        });
    }

    public function down(): void
    {
        Schema::table('payment_methods', function (Blueprint $table) {
            if (!$this->isSqlite() && Schema::hasIndex('payment_methods', ['zone_id'])) {
                $table->dropForeign(['zone_id']);
            }
            $table->dropColumn('zone_id');
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
