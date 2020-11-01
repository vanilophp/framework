<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpgradeMediaTableToV9 extends Migration
{
    public function up()
    {
        Schema::table('media', function (Blueprint $table) {
            $table->json('generated_conversions')->nullable()->after('custom_properties');
        });

        DB::table('media')->cursor()->each(function ($media) {
            DB::table('media')->where('id', $media->id)->update([
                'generated_conversions' => json_encode([])
            ]);
        });

        Schema::table('media', function (Blueprint $table) {
            $table->json('generated_conversions')->nullable(false)->change();
        });
    }

    public function down()
    {
        Schema::table('media', function (Blueprint $table) {
            $table->dropColumn(['generated_conversions']);
        });
    }
}
