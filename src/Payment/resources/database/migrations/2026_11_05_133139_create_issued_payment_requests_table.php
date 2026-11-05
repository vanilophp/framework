<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('issued_payment_requests', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('payment_id');
            // Intentionally not a foreign key: the Snapshot module is an optional dependency
            $table->unsignedBigInteger('snapshot_id')->nullable()->unique();
            $table->timestamps();

            $table->foreign('payment_id')
                  ->references('id')
                  ->on('payments')
                  ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('issued_payment_requests');
    }
};
