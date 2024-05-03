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
            $table->boolean('profission_guide')->default(false);
            $table->string('tax_number')->nullable();
            $table->string('files')->nullable();
            $table->string('url')->nullable();
            $table->string('job')->nullable();
            $table->string('language')->nullable();
            $table->string('banck_name')->nullable();
            $table->string('banck_number')->nullable();
            $table->string('experience_info')->nullable();
            $table->longText('bio')->nullable();
            $table->longText('rerequest_reason')->nullable();
            $table->enum('type', ['company','indivedual'])->default('company');
            $table->longText('description')->nullable();
            $table->string('streat')->nullable();
            $table->string('postal_code')->nullable();
            $table->boolean('active')->default(true);
            $table->foreignId('city_id')->nullable()->references('id')->on('cities')->onDelete('cascade');
            $table->foreignId('country_id')->nullable()->references('id')->on('countries')->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->references('id')->on('users')->onDelete('cascade');
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
