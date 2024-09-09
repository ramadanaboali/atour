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
        Schema::create('effectivenes', function (Blueprint $table) {
          $table->id();
            $table->string('title_ar');
            $table->string('title_en')->nullable();
            $table->longText('description_en')->nullable();
            $table->longText('description_ar')->nullable();
            $table->date('date')->nullable();
            $table->date('time')->nullable();
            $table->decimal('price', 8, 2)->nullable();
            $table->decimal('rate', 4, 0)->nullable();
            $table->text('location')->nullable();
            $table->string('lat')->nullable();
            $table->string('long')->nullable();
            $table->string('cover')->nullable();
            $table->integer('people')->nullable();
            $table->boolean('free_cancelation')->default(false);
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
        Schema::dropIfExists('effectivenes');
    }
};
