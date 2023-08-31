<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('channelables', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('channel_id');
            $table->string('channelable_type');
            $table->unsignedBigInteger('channelable_id');
            $table->timestamps();

            $table->foreign('channel_id')
                ->references('id')
                ->on('channels');
        });
    }

    public function down(): void
    {
        Schema::drop('channelables');
    }
};
