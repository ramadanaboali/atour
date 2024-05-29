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
        Schema::create('trip_programs', function (Blueprint $table) {
                $table->id();
                $table->string('title_en');
                $table->string('title_ar');
                $table->longText('description_en')->nullable();
                $table->longText('description_ar')->nullable();
                $table->decimal('price', 8, 2)->nullable();
                $table->string('start_time')->nullable();
                $table->string('end_time')->nullable();
                $table->string('image')->nullable();
                $table->boolean('active')->default(true);
                $table->foreignId('service_id')->nullable()->references('id')->on('services')->onDelete('cascade');
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
        Schema::dropIfExists('trip_programs');
    }
};
