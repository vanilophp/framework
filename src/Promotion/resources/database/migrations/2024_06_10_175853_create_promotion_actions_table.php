<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('promotion_actions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('promotion_id');
            $table->string('type');
            $table->json('configuration')->nullable();
            $table->timestamps();
            $table->foreign('promotion_id')->references('id')->on('promotions');
        });
    }

    public function down(): void
    {
        Schema::drop('promotion_actions');
    }
};
