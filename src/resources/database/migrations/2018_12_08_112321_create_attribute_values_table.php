<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAttributeValuesTable extends Migration
{
    public function up()
    {
        Schema::create('attribute_values', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('attribute_id')->unsigned();
            $table->string('value');
            $table->string('title');
            $table->integer('priority')->nullable();
            $table->json('settings')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('attribute_id')
                  ->references('id')
                  ->on('attributes')
                  ->onDelete('cascade');

            $table->index('priority');
        });
    }

    public function down()
    {
        Schema::drop('attribute_values');
    }
}
