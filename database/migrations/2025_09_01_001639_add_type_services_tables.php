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
            $table->string('type')->default('individual');
        });

        Schema::table('effectivenes', function (Blueprint $table) {
            $table->string('type')->default('individual');
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
            $table->dropColumn('type');
        });
        Schema::table('effectivenes', function (Blueprint $table) {
            $table->dropColumn('type');
        });
    }
};
