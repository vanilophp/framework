<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Vanilo\Product\Models\ProductStateProxy;

return new class () extends Migration {
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('state')->default(ProductStateProxy::defaultValue())->change();
        });
    }

    public function down(): void
    {
        // Do nothing. We won't reverse this, because it's way too complicated considering
        // Doctrine DBAL's involvement in this, the fragile DBAL v3, v4 incompatibility
        // with Laravel 10-12 + the heavy changes to Laravel 12 migrations internals
    }
};
