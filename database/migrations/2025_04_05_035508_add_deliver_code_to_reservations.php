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
        Schema::table('booking_trips', function (Blueprint $table) {
            $table->string('confirm_code')->nullable();
        });
        Schema::table('booking_gifts', function (Blueprint $table) {
            $table->string('confirm_code')->nullable();
        });
        Schema::table('booking_effectivenes', function (Blueprint $table) {
            $table->string('confirm_code')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('booking_effectivenes', function (Blueprint $table) {
            $table->dropColumn('confirm_code');
        });
        Schema::table('booking_trips', function (Blueprint $table) {
            $table->dropColumn('confirm_code');
        });
        Schema::table('booking_gifts', function (Blueprint $table) {
            $table->dropColumn('confirm_code');
        });
    }
};
