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
        Schema::create('sub_category_translations', function (Blueprint $table) {

            $table->id();
            $table->unsignedBigInteger('sub_category_id');
            $table->string('locale', 5); // en, ar, fr
            $table->text('title')->nullable();
            $table->timestamps();
            $table->unique(['sub_category_id', 'locale']);

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sub_category_translations');
    }
};
