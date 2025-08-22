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
        Schema::table('trip_translations', function (Blueprint $table) {
            $table->json('steps_list')->nullable()->after('description'); // Add steps_list column
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('trip_translations', function (Blueprint $table) {
            $table->dropColumn('steps_list');
        });
    }
};
