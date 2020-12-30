<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePaymentsTable extends Migration
{
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('payment_method_id')->unsigned();
            $table->string('payable_type');
            $table->bigInteger('payable_id')->unsigned();
            $table->string('hash')->nullable();
            $table->json('data')->nullable();
            $table->char('currency', 3);
            $table->decimal('amount', 15, 4);
            $table->decimal('amount_paid', 15, 4)->default(0);
            $table->string('status', 35);
            $table->timestamps();

            $table->foreign('payment_method_id')
                  ->references('id')
                  ->on('payment_methods');

            $table->unique('hash');
        });
    }

    public function down()
    {
        Schema::drop('payments');
    }
}
