<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBillpayersTable extends Migration
{
    public function up()
    {
        Schema::create('billpayers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('email')->nullable();
            $table->string('phone', 22)->nullable();
            $table->string('firstname')->nullable()->comment('First name');
            $table->string('lastname')->nullable()->comment('Last name');
            $table->string('company_name')->nullable();
            $table->string('tax_nr', 17)->nullable()->comment('Tax/VAT Identification Number'); //https://www.wikiwand.com/en/VAT_identification_number
            $table->string('registration_nr')->nullable()->comment('Company/Trade Registration Number');
            $table->boolean('is_eu_registered')->default(false);
            $table->boolean('is_organization')->default(false);
            $table->integer('address_id')->unsigned();
            $table->timestamps();

            $table->foreign('address_id')
                  ->references('id')
                  ->on('addresses');
        });
    }

    public function down()
    {
        Schema::drop('billpayers');
    }
}
