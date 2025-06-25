<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Vanilo\Product\Models\ProductStateProxy;

return new class () extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('state')->default(ProductStateProxy::defaultValue())->change();
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->enum('state', ProductStateProxy::values())->default(ProductStateProxy::defaultValue())->change();
        });
    }
};
