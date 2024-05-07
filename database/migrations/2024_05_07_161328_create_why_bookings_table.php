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
        Schema::create('why_bookings', function (Blueprint $table) {

            $table->id();
            $table->string('title_en');
            $table->string('title_ar');
            $table->string('image')->nullable();

            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();

            $table->longText('description_en')->nullable();
            $table->longText('description_ar')->nullable();
            $table->boolean('active')->default(true);
            $table->foreignId('created_by')->nullable()->references('id')->on('users')->onDelete('cascade');
            $table->foreignId('updated_by')->nullable()->references('id')->on('users')->onDelete('cascade');
            $table->softDeletes();
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
        Schema::dropIfExists('why_bookings');
    }
};
