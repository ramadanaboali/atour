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
        Schema::create('suppliers', function (Blueprint $table) {
            $table->id();
            $table->boolean('tour_guid')->default(false);
            $table->longText('rerequest_reason')->nullable();
            $table->enum('type', ['company','indivedual'])->default('company');
            $table->foreignId('country_id')->nullable()->references('id')->on('countries')->onDelete('cascade');
            $table->foreignId('city_id')->nullable()->references('id')->on('cities')->onDelete('cascade');
            $table->string('streat')->nullable();
            $table->string('postal_code')->nullable();
            $table->foreignId('user_id')->nullable()->references('id')->on('users')->onDelete('cascade');
            $table->longText('description')->nullable();
            $table->longText('short_description')->nullable();
            $table->string('url')->nullable();
            $table->boolean('profission_guide')->default(false);
            $table->string('job')->nullable();
            $table->string('experience_info')->nullable();
            $table->longText('languages')->nullable();
            $table->string('banck_name')->nullable();
            $table->string('banck_number')->nullable();
            $table->string('tax_number')->nullable();
            $table->string('place_summary')->nullable();
            $table->string('place_content')->nullable();
            $table->string('expectations')->nullable();
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
        Schema::dropIfExists('suppliers');
    }
};
