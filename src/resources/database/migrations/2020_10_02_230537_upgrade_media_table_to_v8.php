<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Str;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class UpgradeMediaTableToV8 extends Migration
{
    public function up()
    {
        Schema::table('media', function (Blueprint $table) {
            $table->uuid('uuid')->nullable();
            $table->string('conversions_disk')->nullable();
        });

        Media::create([
            'model_type' => 'Product',
            'model_id' => 1,
            'collection_name' => 'ja',
            'name' => 'ja',
            'file_name' => 'ja',
            'mime_type' => 'image/jpeg',
            'disk' => 'local',
            'size' => 1,
            'manipulations' => '{}',
            'custom_properties' => '{}',
            'responsive_images' => '{}',
        ]);

        // Generate UUID for all records in the table
        DB::table('media')->cursor()->each(function ($media) {
            DB::table('media')->where('id', $media->id)->update(['uuid' => Str::uuid()]);
        });
    }

    public function down()
    {
        Schema::table('media', function (Blueprint $table) {
            $table->dropColumn(['uuid', 'conversions_disk']);
        });
    }
}
