<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentHistoryTable extends Migration
{
    public function up()
    {
        Schema::create('payment_history', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('payment_id');
            $table->string('old_status', 35)->nullable();
            $table->string('new_status', 35);
            $table->string('message')->nullable();
            $table->string('native_status')->nullable();
            $table->string('transaction_number')->nullable();
            $table->decimal('transaction_amount', 15, 4)->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::drop('payment_history');
    }
}
