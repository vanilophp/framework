<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('shipping_methods', static function (Blueprint $table) {
            $table->integer('eta_min')->nullable();
            $table->integer('eta_max')->nullable();
            $table->string('eta_units')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('shipping_methods', static function (Blueprint $table) {
            $table->dropColumn('eta_min');
            $table->dropColumn('eta_max');
            $table->dropColumn('eta_units');
        });
    }
};
