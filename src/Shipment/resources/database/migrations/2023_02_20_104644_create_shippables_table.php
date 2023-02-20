<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up()
    {
        Schema::create('shippables', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('shipment_id');
            $table->string('shippable_type');
            $table->unsignedBigInteger('shippable_id');
            $table->timestamps();

            $table->foreign('shipment_id')
                ->references('id')
                ->on('shipments');
        });
    }

    public function down()
    {
        Schema::drop('shippables');
    }
};
