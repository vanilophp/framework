<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::table('channels', function (Blueprint $table) {
            $table->char('language', 2)->default('en')->comment('The two letter ISO 639-1 language code');
            $table->string('domain', 255)->nullable()->unique();
            $table->string('billing_company', 255)->nullable();
            $table->char('billing_country_id', 2)->nullable();
            $table->integer('billing_province_id')->unsigned()->nullable();
            $table->string('billing_postalcode', 12)->nullable();
            $table->string('billing_city')->nullable();
            $table->string('billing_address', 384)->nullable();
            $table->string('billing_address2', 255)->nullable();
            $table->string('billing_tax_nr', 17)->nullable();
            $table->string('billing_registration_nr')->nullable();
            $table->string('email')->nullable();
            $table->string('phone', 22)->nullable();
            $table->json('billing_countries')->nullable();
            $table->json('shipping_countries')->nullable();
            $table->char('color', 7)->default('#777777');
            $table->string('theme')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('channels', function (Blueprint $table) {
            $table->dropColumn('language');
        });
        Schema::table('channels', function (Blueprint $table) {
            $table->dropUnique('channels_domain_unique');
            $table->dropColumn('domain');
        });
        Schema::table('channels', function (Blueprint $table) {
            $table->dropColumn('billing_company');
        });
        Schema::table('channels', function (Blueprint $table) {
            $table->dropColumn('billing_country_id');
        });
        Schema::table('channels', function (Blueprint $table) {
            $table->dropColumn('billing_province_id');
        });
        Schema::table('channels', function (Blueprint $table) {
            $table->dropColumn('billing_postalcode');
        });
        Schema::table('channels', function (Blueprint $table) {
            $table->dropColumn('billing_city');
        });
        Schema::table('channels', function (Blueprint $table) {
            $table->dropColumn('billing_address');
        });
        Schema::table('channels', function (Blueprint $table) {
            $table->dropColumn('billing_address2');
        });
        Schema::table('channels', function (Blueprint $table) {
            $table->dropColumn('billing_tax_nr');
        });
        Schema::table('channels', function (Blueprint $table) {
            $table->dropColumn('billing_registration_nr');
        });
        Schema::table('channels', function (Blueprint $table) {
            $table->dropColumn('email');
        });
        Schema::table('channels', function (Blueprint $table) {
            $table->dropColumn('phone');
        });
        Schema::table('channels', function (Blueprint $table) {
            $table->dropColumn('billing_countries');
        });
        Schema::table('channels', function (Blueprint $table) {
            $table->dropColumn('shipping_countries');
        });
        Schema::table('channels', function (Blueprint $table) {
            $table->dropColumn('color');
        });
        Schema::table('channels', function (Blueprint $table) {
            $table->dropColumn('theme');
        });
    }
};
