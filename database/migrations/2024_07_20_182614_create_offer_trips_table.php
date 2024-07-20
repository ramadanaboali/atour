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
        Schema::create('offer_trips', function (Blueprint $table) {

            $table->id();
            $table->foreignId('trip_id')->nullable()->references('id')->on('trips')->onDelete('cascade');
            $table->foreignId('offer_id')->nullable()->references('id')->on('offers')->onDelete('cascade');
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('offer_trips');
    }
};
