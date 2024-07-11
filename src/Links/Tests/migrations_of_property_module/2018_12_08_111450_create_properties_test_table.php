<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePropertiesTestTable extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('properties')) {
            Schema::create('properties', function (Blueprint $table) {
                $table->increments('id');
                $table->string('name');
                $table->string('type');
                $table->string('slug')->nullable();
                $table->json('configuration')->nullable();
                $table->softDeletes();
                $table->timestamps();

                $table->unique('slug');
            });
        }
    }

    public function down()
    {
        if (!Schema::hasTable('property_values')) { // this isn't present the test suite is running in standalone mode
            Schema::dropIfExists('properties');
        }
    }
}
