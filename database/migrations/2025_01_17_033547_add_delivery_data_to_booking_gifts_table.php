<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('booking_gifts', function (Blueprint $table) {
            $table->string('delivery_number')->nullable()->after('delivery_address');
            $table->string('location')->nullable();
            $table->string('lat')->nullable();
            $table->string('long')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('booking_gifts', function (Blueprint $table) {
            $table->dropColumn('delivery_number');
            $table->dropColumn('location');
            $table->dropColumn('lat');
            $table->dropColumn('long');
        });
    }
};
