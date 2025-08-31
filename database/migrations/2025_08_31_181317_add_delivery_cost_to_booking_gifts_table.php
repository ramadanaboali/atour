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
            $table->decimal('delivery_cost', 10, 2)->default(0);
            $table->unsignedBigInteger('city_id')->nullable();
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
            $table->dropColumn('delivery_cost');
            $table->dropColumn('city_id');
        });
    }
};
