<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('translations', static function (Blueprint $table) {
            $table->id();
            $table->morphs('translatable');
            $table->char('language', 2)->comment('The two letter ISO 639-1 language code');
            $table->string('name')->nullable();
            $table->string('slug')->nullable();
            $table->boolean('is_published')->default(true);
            $table->json('fields')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('translations');
    }
};
