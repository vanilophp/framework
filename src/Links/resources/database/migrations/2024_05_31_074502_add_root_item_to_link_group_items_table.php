<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('link_groups', function (Blueprint $table) {
            $table->unsignedBigInteger('root_item_id')->nullable();

            $table->foreign('root_item_id')->references('id')->on('link_group_items');
        });
    }

    public function down(): void
    {
        Schema::table('link_groups', function (Blueprint $table) {
            $table->dropColumn('root_item_id');
        });
    }
};
