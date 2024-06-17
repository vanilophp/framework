<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::table('taxons', function (Blueprint $table) {
            // The `subtitle`, `excerpt` and `description` are very generic
            // others may already have such fields, treating them nicely
            $this->addColumnIfNotYetExists($table, 'subtitle', 'string');
            $this->addColumnIfNotYetExists($table, 'excerpt', 'text');
            $this->addColumnIfNotYetExists($table, 'description', 'text');
            $table->text('top_content')->nullable();
            $table->text('bottom_content')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('taxons', function (Blueprint $table) {
            $table->dropColumn('subtitle');
        });
        Schema::table('taxons', function (Blueprint $table) {
            $table->dropColumn('excerpt');
        });
        Schema::table('taxons', function (Blueprint $table) {
            $table->dropColumn('description');
        });
        Schema::table('taxons', function (Blueprint $table) {
            $table->dropColumn('top_content');
        });
        Schema::table('taxons', function (Blueprint $table) {
            $table->dropColumn('bottom_content');
        });
    }

    private function addColumnIfNotYetExists(Blueprint $table, string $column, string $type): void
    {
        if (!Schema::hasColumn('taxons', $column)) {
            match ($type) {
                'text' => $table->text($column)->nullable(),
                'string' => $table->string($column)->nullable(),
                default => 'WTF',
            };
        }
    }
};
