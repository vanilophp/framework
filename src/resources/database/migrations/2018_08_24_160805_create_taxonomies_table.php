<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTaxonomiesTable extends Migration
{
    public function up()
    {
        Schema::create('taxonomies', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('slug')->nullable();
            $table->timestamps();

            $table->unique('slug');
        });
    }

    public function down()
    {
        Schema::drop('taxonomies');
    }
}
