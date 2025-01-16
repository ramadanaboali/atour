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
        Schema::create('trip_requirements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('trip_id')->references('id')->on('trips')->onDelete('cascade');
            $table->foreignId('requirement_id')->references('id')->on('requirements')->onDelete('cascade');
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
        Schema::dropIfExists('trip_requirements');
    }
};
