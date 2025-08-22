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
        Schema::create('trip_translations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('trip_id');
            $table->string('locale', 5); // en, ar, fr
            $table->text('title')->nullable();
            $table->text('start_point')->nullable();
            $table->text('end_point')->nullable();
            $table->text('program_time')->nullable();
            $table->longText('description')->nullable();
            $table->timestamps();
            $table->unique(['trip_id', 'locale']); // كل لغة مرة واحدة فقط
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('trip_translations');
    }
};
