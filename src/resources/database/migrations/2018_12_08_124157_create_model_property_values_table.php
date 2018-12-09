<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateModelPropertyValuesTable extends Migration
{
    public function up()
    {
        Schema::create('model_property_values', function (Blueprint $table) {
            $table->integer('property_value_id')->unsigned();
            $table->morphs('model');
            $table->timestamps();

            $table->foreign('property_value_id')
                ->references('id')
                ->on('property_values')
                ->onDelete('cascade');

            $table->primary(['property_value_id', 'model_id', 'model_type'], 'pk_model_property_values');
        });
    }

    public function down()
    {
        Schema::drop('model_property_values');
    }
}
