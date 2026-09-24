<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::table('taxons', function (Blueprint $table) {
            if (!Schema::hasColumn('taxons', 'is_active')) {
                $table->boolean('is_active')->default(true);
            }
        });
    }

    public function down(): void
    {
        Schema::table('taxons', function (Blueprint $table) {
            $table->dropColumn('is_active');
        });
    }
};
