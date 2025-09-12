<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::table('adjustments', function (Blueprint $table) {
            $table->nullableNumericMorphs('source');
        });
    }

    public function down(): void
    {
        Schema::table('adjustments', function (Blueprint $table) {
            $table->dropMorphs('source');
        });
    }
};
