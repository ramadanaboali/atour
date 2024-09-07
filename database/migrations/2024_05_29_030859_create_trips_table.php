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

        Schema::create('trips', function (Blueprint $table) {
            $table->id();
            $table->string('title_ar');
            $table->string('title_en')->nullable();
            $table->longText('description_en')->nullable();
            $table->longText('description_ar')->nullable();
            $table->decimal('price', 8, 2)->nullable();
            $table->longText('start_point')->nullable();
            $table->string('program_time')->nullable();
            $table->string('cover')->nullable();
            $table->integer('people')->nullable();
            $table->boolean('free_cancelation')->default(false);
            $table->json('available_days')->nullable();
            $table->boolean('pay_later')->default(false);
            $table->boolean('active')->default(true);
            $table->foreignId('city_id')->nullable()->references('id')->on('cities')->onDelete('cascade');
            $table->foreignId('vendor_id')->nullable()->references('id')->on('users')->onDelete('cascade');
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
        Schema::dropIfExists('trips');
    }
};
