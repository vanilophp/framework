<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

return new class () extends Migration {
    public function up()
    {
        Schema::create('shipping_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->boolean('is_fragile')->default(false);
            $table->boolean('is_hazardous')->default(false);
            $table->boolean('is_stackable')->default(true);
            $table->boolean('requires_temperature_control')->default(false);
            $table->boolean('requires_signature')->default(false);
            $table->timestamps();

        });
    }

    public function down()
    {
        Schema::drop('shipping_categories');
    }
};
