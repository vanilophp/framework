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
            $table->integer('parent_id')->unsigned()->nullable();
            $table->integer('priority')->nullable();
            $table->string('name');
            $table->string('slug')->nullable();
            $table->string('ext_title', 511)->nullable();
            $table->text('meta_keywords')->nullable();
            $table->text('meta_description')->nullable();
            $table->timestamps();

            $table->foreign('taxonomy_id')
                ->references('id')
                ->on('taxonomies')
                ->onDelete('cascade');

            $table->foreign('parent_id')
                  ->references('id')
                  ->on('taxons')
                  ->onDelete('cascade');

            $table->unique(['taxonomy_id', 'slug', 'parent_id']);
            $table->index('priority');
        });
    }

    public function down()
    {
        Schema::drop('taxons');
    }
}
