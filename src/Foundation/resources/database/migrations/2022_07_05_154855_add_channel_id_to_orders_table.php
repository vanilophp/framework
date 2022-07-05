<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->intOrBigIntBasedOnRelated('channel_id', Schema::connection(null), 'channels.id')
                ->after('shipping_address_id')
                ->unsigned()
                ->nullable();
        });
    }

    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('channel_id');
        });
    }
};
