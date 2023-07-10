<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChannelsTable extends Migration
{
    public function up()
    {
        Schema::create('channels', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('slug')->nullable();
            $table->json('configuration')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->unique('slug');
        });
    }

    public function down()
    {
        Schema::drop('channels');
    }
}
