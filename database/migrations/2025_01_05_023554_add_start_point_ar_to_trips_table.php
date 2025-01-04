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
        Schema::table('trips', function (Blueprint $table) {
            $table->string('start_point_ar')->nullable();
            $table->string('start_point_en')->nullable();
            $table->dropColumn('start_point');
        });
        Schema::table('gifts', function (Blueprint $table) {
            $table->string('location_ar')->nullable();
            $table->string('location_en')->nullable();
            $table->dropColumn('location');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('trips', function (Blueprint $table) {
            $table->dropColumn('start_point_ar');
            $table->string('start_point')->nullable();
            $table->dropColumn('start_point_en');
        });
        Schema::table('gifts', function (Blueprint $table) {
            $table->dropColumn('location_ar');
            $table->string('location')->nullable();
            $table->dropColumn('location_en');
        });
    }
};
