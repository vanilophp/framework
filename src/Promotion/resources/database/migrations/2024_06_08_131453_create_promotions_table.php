<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('promotions', function (Blueprint $table) {
            $table->id();

            $table->string('code')->unique();
            $table->string('name');
            $table->text('description')->nullable();
            $table->integer('priority')->default(0);
            $table->boolean('exclusive')->default(false);
            $table->unsignedInteger('usage_limit')->nullable();
            $table->unsignedInteger('used')->default(0);
            $table->boolean('coupon_based')->default(0);
            $table->dateTime('starts_at')->nullable();
            $table->dateTime('ends_at')->nullable();
            $table->boolean('applies_to_discounted')->default(1);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::drop('promotions');
    }
};
