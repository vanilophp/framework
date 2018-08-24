<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTaxonsTable extends Migration
{
    public function up()
    {
        Schema::create('taxons', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('taxonomy_id')->unsigned();
            $table->string('name');
            $table->string('slug')->nullable();
            $table->string('ext_title', 511)->nullable();
            $table->text('meta_keywords')->nullable();
            $table->text('meta_description')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('taxonomy_id')
                ->references('id')
                ->on('taxonomies')
                ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::drop('taxons');
    }
}
