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
        Schema::table('trips', function (Blueprint $table) {
            $table->dropColumn('lat');
            $table->dropColumn('long');

            $table->string("start_lat")->nullable();
            $table->string("start_long")->nullable();
            $table->string("end_lat")->nullable();
            $table->string("end_long")->nullable();
            $table->string("end_point_ar")->nullable();
            $table->string("end_point_en")->nullable();

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

            $table->string("lat")->nullable();
            $table->string("long")->nullable();

            $table->dropColumn("start_lat");
            $table->dropColumn("start_long");
            $table->dropColumn("end_lat");
            $table->dropColumn("end_long");
            $table->dropColumn("end_point_ar");
            $table->dropColumn("end_point_en");


        });
    }
};
