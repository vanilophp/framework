<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateModelAttributeValuesTable extends Migration
{
    public function up()
    {
        Schema::create('model_attribute_values', function (Blueprint $table) {
            $table->integer('attribute_value_id')->unsigned();
            $table->morphs('model');
            $table->timestamps();

            $table->foreign('attribute_value_id')
                ->references('id')
                ->on('attribute_values')
                ->onDelete('cascade');

            $table->primary(['attribute_value_id', 'model_id', 'model_type']);
        });
    }

    public function down()
    {
        Schema::drop('model_attribute_values');
    }
}
