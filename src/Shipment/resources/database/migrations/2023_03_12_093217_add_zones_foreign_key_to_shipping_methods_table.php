<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up()
    {
        Schema::table('shipping_methods', function (Blueprint $table) {
            $table->foreign('zone_id')->references('id')->on('zones');
        });
    }

    public function down()
    {
        Schema::table('shipping_methods', function (Blueprint $table) {
            if (!$this->isSqlite()) {
                $table->dropForeign(['zone_id']);
            }
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
