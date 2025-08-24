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
        
        Schema::dropIfExists('setting_translations');
        Schema::create('setting_translations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('setting_id');
            $table->string('locale', 5); // en, ar, fr
            $table->string('type', 50); // type of setting
            $table->longText('content')->nullable();
            $table->timestamps();
            $table->unique(['setting_id', 'locale', 'type']); // كل لغة مرة واحدة فقط
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('setting_translations');
    }
};
