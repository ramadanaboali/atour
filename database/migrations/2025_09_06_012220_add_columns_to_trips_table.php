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
            $table->boolean('is_group')->default(false);
        });
        Schema::table('effectivenes', function (Blueprint $table) {

            $table->integer('min_people')->nullable();
            $table->integer('max_people')->nullable();
            $table->dropColumn('people');

            $table->boolean('is_group')->default(false);
        });
        Schema::table('gifts', function (Blueprint $table) {
            $table->integer('quantity')->default(1);
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
            $table->dropColumn('is_group');
        });
        Schema::table('effectivenes', function (Blueprint $table) {
            $table->dropColumn('min_people');
            $table->dropColumn('max_people');
            $table->integer('people')->nullable();
            $table->dropColumn('is_group');
        });
        Schema::table('gifts', function (Blueprint $table) {
            $table->dropColumn('quantity');
        });
    }
};
