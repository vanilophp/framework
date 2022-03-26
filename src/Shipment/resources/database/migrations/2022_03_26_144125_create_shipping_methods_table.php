<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

return new class extends Migration
{
    public function up()
    {
        Schema::create('shipping_methods', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->bigInteger('carrier_id')->nullable();
            $table->json('configuration')->nullable();
            $table->timestamps();

            $table->foreign('carrier_id')
                  ->references('id')
                  ->on('carriers');
        });
    }

    public function down()
    {
        Schema::drop('shipping_methods');
    }
};
