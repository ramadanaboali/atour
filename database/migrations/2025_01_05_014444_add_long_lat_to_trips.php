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
            $table->string("lat")->nullable();
            $table->string("long")->nullable();
        });
        Schema::table('gifts', function (Blueprint $table) {
            $table->string("lat")->nullable();
            $table->string("long")->nullable();
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
            $table->dropColumn('lat');
            $table->dropColumn('long');
        });
        Schema::table('gifts', function (Blueprint $table) {
            $table->dropColumn('lat');
            $table->dropColumn('long');
        });
    }
};
