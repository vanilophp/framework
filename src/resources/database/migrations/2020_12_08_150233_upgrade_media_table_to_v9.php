<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpgradeMediaTableToV9 extends Migration
{
    public function up()
    {
        Schema::table('media', function (Blueprint $table) {
            $table->json('generated_conversions')->default('{}');
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
}
