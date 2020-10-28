<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Str;

class UpgradeMediaTableToV8 extends Migration
{
    public function up()
    {
        Schema::table('media', function (Blueprint $table) {
            $table->uuid('uuid')->nullable();
            $table->string('conversions_disk')->nullable();
        });

        // Generate UUID for all records in the table
        DB::table('media')->cursor()->each(function ($media) {
            DB::table('media')->where('id', $media->id)->update([
                'uuid'             => Str::uuid(),
                'conversions_disk' => $media->disk                                                                ]);
        });
    }

    public function down()
    {
        Schema::table('media', function (Blueprint $table) {
            $table->dropColumn(['uuid', 'conversions_disk']);
        });
    }
}
