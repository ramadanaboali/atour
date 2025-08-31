<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('offers', function (Blueprint $table) {
            $table->dropForeign(['gift_id']);
            $table->dropForeign(['effectivenes_id']);
            $table->dropForeign(['trip_id']);
            $table->dropForeign(['vendor_id']);
            $table->dropColumn(['vendor_id', 'trip_id', 'effectivenes_id', 'gift_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('offers', function (Blueprint $table) {
            $table->unsignedBigInteger('vendor_id')->nullable();
            $table->unsignedBigInteger('trip_id')->nullable();
            $table->unsignedBigInteger('effectivenes_id')->nullable();
            $table->unsignedBigInteger('gift_id')->nullable();
        });
    }
};
