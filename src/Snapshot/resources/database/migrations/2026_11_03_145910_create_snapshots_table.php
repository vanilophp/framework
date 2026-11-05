<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('snapshots', static function (Blueprint $table) {
            $table->id();
            $table->timestamp('taken_at');
            $table->timestamp('valid_until')->nullable();
            $table->string('type');
            $table->unsignedInteger('version')->default(1);
            $table->nullableMorphs('snapshottable');
            $table->json('data');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('snapshots');
    }
};
