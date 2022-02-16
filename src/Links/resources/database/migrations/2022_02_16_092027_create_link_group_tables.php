<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLinkGroupTables extends Migration
{
    public function up()
    {
        Schema::create('link_groups', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('link_type_id')->unsigned();
            $table->bigInteger('property_id')->nullable();
            $table->timestamps();

            $table->foreign('link_type_id')
                ->references('id')
                ->on('link_types');

            if (Schema::hasTable('properties')) {
                $table->foreign('property_id')
                    ->references('id')
                    ->on('properties');
            }
        });

        Schema::create('link_group_items', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('link_group id')->unsigned();
            $table->bigInteger('model_id')->unsigned();
            $table->string('model_type');

            $table->foreign('link_group id')
                ->references('id')
                ->on('link_groups')
                ->onDelete('cascade');

            $table->unique(['link_group_id', 'model_id', 'model_type']);
        });
    }

    public function down()
    {
        Schema::drop('link_group_items');
        Schema::drop('link_groups');
    }
}
