<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up()
    {
        Schema::create('tax_rates', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->bigInteger('tax_category_id')->unsigned()->nullable();
            $table->unsignedBigInteger('zone_id')->nullable();
            $table->decimal('rate', 15, 4);
            $table->string('calculator')->nullable();
            $table->json('configuration')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamp('valid_from')->nullable();
            $table->timestamp('valid_until')->nullable();
            $table->timestamps();

            $table->foreign('tax_category_id')->references('id')->on('tax_categories');
            $table->foreign('zone_id')->references('id')->on('zones');
        });
    }

    public function down()
    {
        Schema::drop('tax_rates');
    }
};
