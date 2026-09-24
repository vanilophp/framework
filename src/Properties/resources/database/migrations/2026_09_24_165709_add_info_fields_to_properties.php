<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::table('properties', function (Blueprint $table) {
            $table->text('excerpt')->nullable()->after('slug');
            $table->text('description')->nullable()->after('slug');
        });
    }

    public function down(): void
    {
        Schema::table('properties', function (Blueprint $table) {
            $table->dropColumn('excerpt');
        });
        Schema::table('properties', function (Blueprint $table) {
            $table->dropColumn('description');
        });
    }
};
