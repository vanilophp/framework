<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateShipmentsTable extends Migration
{
    public function up()
    {
        Schema::create('shipments', function (Blueprint $table) {
            $table->increments('id');
            $table->string('tracking_number')->nullable();
            $table->integer('address_id')->unsigned();
            $table->boolean('is_trackable')->default(true);
            $table->string('status')->default('new');
            $table->decimal('weight', 15, 4)->nullable();
            $table->decimal('height', 15, 4)->nullable();
            $table->decimal('width', 15, 4)->nullable();
            $table->decimal('length', 15, 4)->nullable();
            $table->text('comment')->nullable();
            $table->json('configuration')->nullable();
            $table->timestamps();

            $table->foreign('address_id')
                  ->references('id')
                  ->on('addresses');
        });
    }

    public function down()
    {
        Schema::drop('shipments');
    }
}
