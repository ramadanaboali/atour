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
        Schema::table('offers', function (Blueprint $table) {
            $table->boolean('active')->default(false);
            $table->enum('type',['effectivenes','gift','trip'])->default('trip');
            $table->foreignId('gift_id')->nullable()->references('id')->on('gifts')->onDelete('cascade');
            $table->foreignId('effectivenes_id')->nullable()->references('id')->on('effectivenes')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('offers', function (Blueprint $table) {
            $table->dropForeign(['gift_id']);
            $table->dropForeign(['effectivenes_id']);
            $table->dropColumn('effectivenes_id');
            $table->dropColumn('gift_id');
            $table->dropColumn('active');
            $table->dropColumn('type');
        });
    }
};
