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
        Schema::create('requirement_translations', function (Blueprint $table) {

            $table->id();
            $table->unsignedBigInteger('requirement_id');
            $table->string('locale', 5); // en, ar, fr
            $table->text('title')->nullable();
            $table->longText('description')->nullable();
            $table->timestamps();
            $table->unique(['requirement_id', 'locale']); // كل لغة مرة واحدة فقط

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('requirement_translations');
    }
};
