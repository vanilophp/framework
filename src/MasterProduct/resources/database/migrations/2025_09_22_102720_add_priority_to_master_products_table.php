<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::table('master_products', function (Blueprint $table) {
            $table->integer('priority')->default(0);
        });
    }

    public function down(): void
    {
        Schema::table('master_products', function (Blueprint $table) {
            $table->dropColumn('priority');
        });
    }
};
