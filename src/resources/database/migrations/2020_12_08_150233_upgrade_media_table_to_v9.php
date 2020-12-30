<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpgradeMediaTableToV9 extends Migration
{
    public function up()
    {
        Schema::table('media', function (Blueprint $table) {
            if ($this->isSqlite()) {
                $table->json('generated_conversions')->default('{}');
            } else {
                $table->json('generated_conversions')->nullable();
            }

            $table->unique('uuid', 'ix_unique_uuid');
        });
    }

    public function down()
    {
        Schema::table('media', function (Blueprint $table) {
            $table->dropColumn('generated_conversions');
            $table->dropUnique('ix_unique_uuid');
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
}
