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
        Schema::table('booking_gifts', function (Blueprint $table) {
            $table->decimal('admin_value',10,2)->default(0);
            $table->tinyInteger('admin_value_status')->default(0);
        });
        Schema::table('booking_trips', function (Blueprint $table) {
            $table->decimal('admin_value',10,2)->default(0);
            $table->tinyInteger('admin_value_status')->default(0);
        });
        Schema::table('booking_effectivenes', function (Blueprint $table) {
            $table->decimal('admin_value',10,2)->default(0);
            $table->tinyInteger('admin_value_status')->default(0);
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
            $table->dropColumn('admin_value');
            $table->dropColumn('admin_value_status');
        });
        Schema::table('booking_trips', function (Blueprint $table) {
            $table->dropColumn('admin_value');
            $table->dropColumn('admin_value_status');
        });
        Schema::table('booking_effectivenes', function (Blueprint $table) {
            $table->dropColumn('admin_value');
            $table->dropColumn('admin_value_status');
        });
    }
};
