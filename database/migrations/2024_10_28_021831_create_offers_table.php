<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        Schema::dropIfExists('offer_services');
        Schema::dropIfExists('offers');
        Schema::create('offers', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('image')->nullable();
            $table->longText('description')->nullable();
            $table->foreignId('trip_id')->nullable()->references('id')->on('trips')->onDelete('cascade');
            $table->foreignId('vendor_id')->nullable()->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
        });
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Schema::dropIfExists('offers');
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
};
