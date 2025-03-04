<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::table('master_product_variants', function (Blueprint $table) {
            $table->string('gtin')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('master_product_variants', function (Blueprint $table) {
            $table->dropColumn('gtin');
        });
    }
};
