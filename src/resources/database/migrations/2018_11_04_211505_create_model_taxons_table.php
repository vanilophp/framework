<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateModelTaxonsTable extends Migration
{
    public function up()
    {
        Schema::create('model_taxons', function (Blueprint $table) {
            $table->integer('taxon_id')->unsigned();
            $table->morphs('model');
            $table->timestamps();

            $table->foreign('taxon_id')
                ->references('id')
                ->on('taxons')
                ->onDelete('cascade');

            $table->primary(['taxon_id', 'model_id', 'model_type']);
        });
    }

    public function down()
    {
        Schema::drop('model_taxons');
    }
}
