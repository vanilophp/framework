<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaymentsTable extends Migration
{
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('type');
            $table->string('hash')->nullable();
            $table->json('configuration')->nullable();
            $table->timestamps();

            $table->unique('hash');
        });
    }

    public function down()
    {
        Schema::drop('payments');
    }
}
